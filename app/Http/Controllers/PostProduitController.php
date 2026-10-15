<?php

namespace App\Http\Controllers;

use App\Models\PostProduit;
use App\Models\PostTravail;
use App\Models\Produit;
use Illuminate\Http\Request;

class PostProduitController extends Controller
{
    public function index(Request $request)
    {
        $query = PostProduit::with(['produit', 'postTravail']);
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
            'postTravails' => PostTravail::orderBy('libele')->pluck('libele', 'id'),
        ]);
    }

    public function create()
    {
        return $this->form(new PostProduit(), 'Ajouter un produit à un poste');
    }

    public function store(Request $request)
    {
        PostProduit::create($request->validate($this->rules()));

        return to_route('post-produits.index')->with('success', 'Produit ajouté au poste avec succès.');
    }

    public function show(int $id)
    {
        $record = PostProduit::with(['produit', 'postTravail'])->findOrFail($id);

        return view('post-produits.show', compact('record'));
    }

    public function edit(int $id)
    {
        return $this->form(PostProduit::findOrFail($id), 'Modifier le produit du poste');
    }

    public function update(Request $request, int $id)
    {
        $record = PostProduit::findOrFail($id);
        $record->update($request->validate($this->rules()));

        return to_route('post-produits.index')->with('success', 'Produit de poste modifié avec succès.');
    }

    public function destroy(int $id)
    {
        PostProduit::findOrFail($id)->delete();

        return to_route('post-produits.index')->with('success', 'Produit retiré du poste avec succès.');
    }

    private function rules(): array
    {
        return [
            'post_travail_id' => ['nullable', 'integer', 'exists:post_travails,id'],
            'produit_id' => ['nullable', 'integer', 'exists:produits,id'],
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
            'postTravails' => PostTravail::orderBy('libele')->pluck('libele', 'id'),
        ]);
    }
}
