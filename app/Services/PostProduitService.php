<?php

namespace App\Services;

use App\Models\PostProduit;
use App\Models\PostTravail;
use App\Models\Produit;
use App\Support\CurrentPostTravail;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PostProduitService
{
    public function index(Request $request): array
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

        return [
            'message' => 'Liste des produits par poste chargée.',
            'data' => [
                'postProduits' => $query->latest()->paginate(15)->withQueryString(),
                'produits' => Produit::orderBy('libele')->pluck('libele', 'id'),
                'postTravails' => $this->availablePosts()->pluck('libele', 'id'),
            ],
        ];
    }

    public function formData(?int $id = null): array
    {
        $record = $id === null
            ? new PostProduit(['post_travail_id' => CurrentPostTravail::id()])
            : $this->scopedQuery()->findOrFail($id);

        return [
            'message' => 'Formulaire produit-poste chargé.',
            'data' => [
                'record' => $record,
                'produits' => Produit::orderBy('libele')->pluck('libele', 'id'),
                'postTravails' => $this->availablePosts()->pluck('libele', 'id'),
            ],
        ];
    }

    public function store(Request $request): array
    {
        $validated = $request->validate($this->rules());
        $this->assertPostAllowed((int) $validated['post_travail_id']);
        $record = PostProduit::create($validated);

        return ['message' => 'Produit ajouté au poste avec succès.', 'data' => $record];
    }

    public function show(int $id): array
    {
        $record = $this->scopedQuery()->with(['produit', 'postTravail'])->findOrFail($id);

        return ['message' => 'Produit du poste chargé.', 'data' => compact('record')];
    }

    public function update(Request $request, int $id): array
    {
        $record = $this->scopedQuery()->findOrFail($id);
        $validated = $request->validate($this->rules());
        $this->assertPostAllowed((int) $validated['post_travail_id']);
        $record->update($validated);

        return ['message' => 'Produit de poste modifié avec succès.', 'data' => $record];
    }

    public function delete(int $id): array
    {
        $this->scopedQuery()->findOrFail($id)->delete();

        return ['message' => 'Produit retiré du poste avec succès.', 'data' => null];
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
