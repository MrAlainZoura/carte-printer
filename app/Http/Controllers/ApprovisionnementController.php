<?php

namespace App\Http\Controllers;

use App\Models\Approvisionnement;
use App\Models\PostProduit;
use App\Models\PostTravail;
use App\Models\Produit;
use App\Support\CurrentPostTravail;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ApprovisionnementController extends Controller
{
    public function index()
    {
        $query = $this->scopedQuery()->with(['user', 'postTravail', 'produit', 'receptionnaire']);

        return view('approvisionnements.index', [
            'approvisionnements' => $query->latest()->paginate(15),
        ]);
    }

    public function create()
    {
        return view('approvisionnements.form', [
            'record' => new Approvisionnement([
                'posttravail_id' => CurrentPostTravail::id(),
                'origine' => 'central',
            ]),
            'posts' => $this->availablePosts(),
            'produits' => Produit::query()->orderBy('libele')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate($this->rules());
        $this->assertPostAllowed((int) $validated['posttravail_id']);

        Approvisionnement::create([
            ...$validated,
            'user_id' => $request->user()->id,
            'confirm' => false,
            'receptionUser' => null,
            'origine' => $validated['origine'] ?? 'central',
        ]);

        return to_route('approvisionnements.index')->with('success', 'Approvisionnement enregistré.');
    }

    public function show(int $id)
    {
        $approvisionnement = $this->scopedQuery()
            ->with(['user', 'postTravail', 'produit', 'receptionnaire'])
            ->findOrFail($id);

        return view('approvisionnements.show', compact('approvisionnement'));
    }

    public function edit(int $id)
    {
        $record = $this->scopedQuery()->findOrFail($id);
        abort_if($record->confirm, 403, 'Un approvisionnement confirmé ne peut plus être modifié.');

        return view('approvisionnements.form', [
            'record' => $record,
            'posts' => $this->availablePosts(),
            'produits' => Produit::query()->orderBy('libele')->get(),
        ]);
    }

    public function update(Request $request, int $id)
    {
        $record = $this->scopedQuery()->findOrFail($id);
        abort_if($record->confirm, 403, 'Un approvisionnement confirmé ne peut plus être modifié.');

        $validated = $request->validate($this->rules());
        $this->assertPostAllowed((int) $validated['posttravail_id']);
        $record->update([
            ...$validated,
            'origine' => $validated['origine'] ?? 'central',
        ]);

        return to_route('approvisionnements.show', $record)->with('success', 'Approvisionnement modifié.');
    }

    public function destroy(int $id)
    {
        $record = $this->scopedQuery()->findOrFail($id);
        abort_if($record->confirm, 403, 'Un approvisionnement confirmé ne peut plus être supprimé.');
        $record->delete();

        return to_route('approvisionnements.index')->with('success', 'Approvisionnement supprimé.');
    }

    public function confirm(int $id, Request $request)
    {
        DB::transaction(function () use ($id, $request) {
            $record = $this->scopedQuery()->lockForUpdate()->findOrFail($id);
            abort_if($record->confirm, 422, 'Cet approvisionnement est déjà confirmé.');

            $record->update([
                'confirm' => true,
                'receptionUser' => $request->user()->id,
            ]);

            $stock = PostProduit::query()->firstOrCreate(
                [
                    'post_travail_id' => $record->posttravail_id,
                    'produit_id' => $record->produit_id,
                ],
                ['quantite' => 0]
            );
            $stock->increment('quantite', $record->quantite);
        });

        return to_route('approvisionnements.show', $id)->with('success', 'Réception confirmée.');
    }

    private function scopedQuery()
    {
        $query = Approvisionnement::query();
        $postId = CurrentPostTravail::id();

        if ($postId !== null) {
            return $query->where('posttravail_id', $postId);
        }

        abort_unless(auth()->user()?->hasRole('admin') && CurrentPostTravail::isGlobal(), 403);

        return $query;
    }

    private function availablePosts()
    {
        if ($postId = CurrentPostTravail::id()) {
            return PostTravail::query()->whereKey($postId)->get();
        }

        abort_unless(auth()->user()?->hasRole('admin') && CurrentPostTravail::isGlobal(), 403);

        return PostTravail::query()->orderBy('libele')->get();
    }

    private function assertPostAllowed(int $postId): void
    {
        $selectedPostId = CurrentPostTravail::id();

        if ($selectedPostId !== null) {
            abort_unless($postId === $selectedPostId, 403);

            return;
        }

        abort_unless(
            auth()->user()?->hasRole('admin')
                && CurrentPostTravail::isGlobal()
                && PostTravail::query()->whereKey($postId)->exists(),
            403
        );
    }

    private function rules(): array
    {
        return [
            'posttravail_id' => ['required', 'integer', Rule::exists('post_travails', 'id')],
            'produit_id' => ['required', 'integer', Rule::exists('produits', 'id')],
            'quantite' => ['required', 'numeric', 'gt:0'],
            'origine' => ['nullable', 'string', 'max:255'],
        ];
    }
}
