<?php

namespace App\Http\Controllers;

use App\Models\Produit;
use Illuminate\Http\Request;

class ProduitController extends Controller
{
    public function index(Request $request)
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

        return view('produits.index', [
            'produits' => $query->latest()->paginate(15)->withQueryString(),
            'categories' => Produit::query()->whereNotNull('categorie')->distinct()->orderBy('categorie')->pluck('categorie'),
        ]);
    }

    public function create()
    {
        return $this->form(new Produit(), 'Créer un produit');
    }

    public function store(Request $request)
    {
        Produit::create($request->validate($this->rules()));

        return to_route('produits.index')->with('success', 'Produit créé avec succès.');
    }

    public function show(int $id)
    {
        $produit = Produit::withCount('postProduits')->findOrFail($id);

        return view('produits.show', compact('produit'));
    }

    public function edit(int $id)
    {
        return $this->form(Produit::findOrFail($id), 'Modifier le produit');
    }

    public function update(Request $request, int $id)
    {
        $produit = Produit::findOrFail($id);
        $produit->update($request->validate($this->rules()));

        return to_route('produits.index')->with('success', 'Produit modifié avec succès.');
    }

    public function destroy(int $id)
    {
        Produit::findOrFail($id)->delete();

        return to_route('produits.index')->with('success', 'Produit supprimé avec succès.');
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

    private function form(Produit $produit, string $title)
    {
        return view('produits.form', [
            'title' => $title,
            'record' => $produit,
        ]);
    }
}
