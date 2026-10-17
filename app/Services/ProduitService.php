<?php

namespace App\Services;

use App\Models\Produit;
use Illuminate\Http\Request;

class ProduitService
{
    public function index(Request $request): array
    {
        $query = Produit::query();
        if ($search = $request->string('search')->trim()->toString()) {
            $query->where(fn ($builder) => $builder
                ->where('libele', 'like', "%{$search}%")
                ->orWhere('marque', 'like', "%{$search}%")
                ->orWhere('categorie', 'like', "%{$search}%"));
        }
        if ($category = $request->string('categorie')->trim()->toString()) {
            $query->where('categorie', $category);
        }

        return [
            'message' => 'Liste des produits chargée.',
            'data' => [
                'produits' => $query->latest()->paginate(15)->withQueryString(),
                'categories' => Produit::query()->whereNotNull('categorie')->distinct()->orderBy('categorie')->pluck('categorie'),
            ],
        ];
    }

    public function formData(?int $id = null): array
    {
        $produit = $id === null ? new Produit() : Produit::findOrFail($id);

        return [
            'message' => 'Formulaire produit chargé.',
            'data' => ['record' => $produit],
        ];
    }

    public function store(Request $request): array
    {
        $produit = Produit::create($request->validate($this->rules()));

        return ['message' => 'Produit créé avec succès.', 'data' => $produit];
    }

    public function show(int $id): array
    {
        $produit = Produit::withCount('postProduits')->findOrFail($id);

        return ['message' => 'Produit chargé.', 'data' => compact('produit')];
    }

    public function update(Request $request, int $id): array
    {
        $produit = Produit::findOrFail($id);
        $produit->update($request->validate($this->rules()));

        return ['message' => 'Produit modifié avec succès.', 'data' => $produit];
    }

    public function delete(int $id): array
    {
        Produit::findOrFail($id)->delete();

        return ['message' => 'Produit supprimé avec succès.', 'data' => null];
    }

    private function rules(): array
    {
        return [
            'libele' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:255'],
            'marque' => ['nullable', 'string', 'max:255'],
            'categorie' => ['nullable', 'string', 'max:255'],
            'unite' => ['nullable', 'string', 'max:255'],
            'etat' => ['nullable', 'string', 'max:255'],
        ];
    }
}
