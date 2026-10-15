<?php

namespace App\Http\Controllers;

use App\Models\PostProduit;
use App\Models\PostTravail;
use App\Models\Produit;
use App\Support\CurrentPostTravail;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PostProduitController extends Controller
{
    public function index(Request $request)
    {
        $query = $this->scopedQuery()->with(['produit', 'postTravail']);
        if ($search = $request->string('search')->trim()->toString()) {
            $query->where(fn ($builder) => $builder
                ->whereHas('produit', fn ($product) => $product->where('libele', 'like', "%{$search}%"))
                ->orWhereHas('postTravail', fn ($post) => $post->where('libele', 'like', "%{$search}%")));
        }
        if ($productId = $request->integer('produit_id')) {
            $query->where('produit_id', $productId);
        }
        if ($postId = $request->integer('post_travail_id')) {
            $query->where('post_travail_id', $postId);
        }

        return view('post-produits.index', [
            'postProduits' => $query->latest()->paginate(15)->withQueryString(),
            'produits' => Produit::orderBy('libele')->pluck('libele', 'id'),
            'postTravails' => $this->availablePosts()->pluck('libele', 'id'),
        ]);
    }

    public function create()
    {
        return $this->form(
            new PostProduit(['post_travail_id' => CurrentPostTravail::id()]),
            'Ajouter un produit à un poste'
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate($this->rules());
        $this->assertPostAllowed((int) $validated['post_travail_id']);
        PostProduit::create($validated);

        return to_route('post-produits.index')->with('success', 'Produit ajouté au poste avec succès.');
    }

    public function show(int $id)
    {
        $record = $this->scopedQuery()->with(['produit', 'postTravail'])->findOrFail($id);

        return view('post-produits.show', compact('record'));
    }

    public function edit(int $id)
    {
        return $this->form($this->scopedQuery()->findOrFail($id), 'Modifier le produit du poste');
    }

    public function update(Request $request, int $id)
    {
        $record = $this->scopedQuery()->findOrFail($id);
        $validated = $request->validate($this->rules());
        $this->assertPostAllowed((int) $validated['post_travail_id']);
        $record->update($validated);

        return to_route('post-produits.index')->with('success', 'Produit de poste modifié avec succès.');
    }

    public function destroy(int $id)
    {
        $this->scopedQuery()->findOrFail($id)->delete();

        return to_route('post-produits.index')->with('success', 'Produit retiré du poste avec succès.');
    }

    private function rules(): array
    {
        return [
            'post_travail_id' => ['required', 'integer', Rule::exists('post_travails', 'id')],
            'produit_id' => ['required', 'integer', Rule::exists('produits', 'id')],
            'prix' => ['nullable', 'numeric', 'min:0'],
            'quantite' => ['required', 'numeric', 'min:0'],
        ];
    }

    private function form(PostProduit $record, string $title)
    {
        return view('post-produits.form', [
            'title' => $title,
            'record' => $record,
            'produits' => Produit::orderBy('libele')->pluck('libele', 'id'),
            'postTravails' => $this->availablePosts()->pluck('libele', 'id'),
        ]);
    }

    private function scopedQuery()
    {
        $query = PostProduit::query();
        $postId = CurrentPostTravail::id();

        if ($postId !== null) {
            return $query->where('post_travail_id', $postId);
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
}
