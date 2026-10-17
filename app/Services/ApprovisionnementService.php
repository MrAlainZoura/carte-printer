<?php

namespace App\Services;

use App\Models\Approvisionnement;
use App\Models\PostProduit;
use App\Models\PostTravail;
use App\Models\Produit;
use App\Support\CurrentPostTravail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ApprovisionnementService
{
    public function index(): array
    {
        $approvisionnements = $this->scopedQuery()
            ->with(['user', 'postTravail', 'produit', 'receptionnaire'])
            ->latest()
            ->paginate(15);

        return ['message' => 'Liste des approvisionnements chargée.', 'data' => compact('approvisionnements')];
    }

    public function formData(?int $id = null): array
    {
        $record = $id === null
            ? new Approvisionnement([
                'posttravail_id' => CurrentPostTravail::id(),
                'origine' => 'central',
            ])
            : $this->scopedQuery()->findOrFail($id);

        abort_if($record->confirm, 403, 'Un approvisionnement confirmé ne peut plus être modifié.');

        return [
            'message' => 'Formulaire d’approvisionnement chargé.',
            'data' => [
                'record' => $record,
                'posts' => $this->availablePosts(),
                'produits' => Produit::query()->orderBy('libele')->get(),
            ],
        ];
    }

    public function store(Request $request): array
    {
        $validated = $request->validate($this->rules());
        $this->assertPostAllowed((int) $validated['posttravail_id']);

        $record = Approvisionnement::create([
            ...$validated,
            'user_id' => $request->user()->id,
            'confirm' => false,
            'receptionUser' => null,
            'origine' => $validated['origine'] ?? 'central',
        ]);

        return ['message' => 'Approvisionnement enregistré.', 'data' => $record];
    }

    public function show(int $id): array
    {
        $approvisionnement = $this->scopedQuery()
            ->with(['user', 'postTravail', 'produit', 'receptionnaire'])
            ->findOrFail($id);

        return ['message' => 'Approvisionnement chargé.', 'data' => compact('approvisionnement')];
    }

    public function update(Request $request, int $id): array
    {
        $record = $this->scopedQuery()->findOrFail($id);
        abort_if($record->confirm, 403, 'Un approvisionnement confirmé ne peut plus être modifié.');

        $validated = $request->validate($this->rules());
        $this->assertPostAllowed((int) $validated['posttravail_id']);
        $record->update([
            ...$validated,
            'origine' => $validated['origine'] ?? 'central',
        ]);

        return ['message' => 'Approvisionnement modifié.', 'data' => $record];
    }

    public function delete(int $id): array
    {
        $record = $this->scopedQuery()->findOrFail($id);
        abort_if($record->confirm, 403, 'Un approvisionnement confirmé ne peut plus être supprimé.');
        $record->delete();

        return ['message' => 'Approvisionnement supprimé.', 'data' => null];
    }

    public function confirm(int $id, Request $request): array
    {
        $record = DB::transaction(function () use ($id, $request) {
            $record = $this->scopedQuery()->lockForUpdate()->findOrFail($id);
            abort_if($record->confirm, 422, 'Cet approvisionnement est déjà confirmé.');

            $stock = PostProduit::query()
                ->where('post_travail_id', $record->posttravail_id)
                ->where('produit_id', $record->produit_id)
                ->lockForUpdate()
                ->first();

            if (! $stock) {
                $stock = PostProduit::create([
                    'post_travail_id' => $record->posttravail_id,
                    'produit_id' => $record->produit_id,
                    'quantite' => 0,
                ]);
            }

            $stock->increment('quantite', $record->quantite);
            $record->update([
                'confirm' => true,
                'receptionUser' => $request->user()->id,
            ]);

            return $record->fresh(['user', 'postTravail', 'produit', 'receptionnaire']);
        });

        return ['message' => 'Réception confirmée.', 'data' => $record];
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
