<?php

namespace App\Http\Controllers;

use App\Services\PostProduitService;
use Illuminate\Http\Request;

class PostProduitController extends Controller
{
    public function __construct(private readonly PostProduitService $service)
    {
    }

    public function index(Request $request)
    {
        $result = $this->service->index($request);

        return view('post-produits.index', $result['data']);
    }

    public function create()
    {
        $result = $this->service->formData();

        return view('post-produits.form', [
            'title' => 'Ajouter un produit à un poste',
            ...$result['data'],
        ]);
    }

    public function store(Request $request)
    {
        $result = $this->service->store($request);

        return to_route('post-produits.index')->with('success', $result['message']);
    }

    public function show(int $id)
    {
        $result = $this->service->show($id);

        return view('post-produits.show', $result['data']);
    }

    public function edit(int $id)
    {
        $result = $this->service->formData($id);

        return view('post-produits.form', [
            'title' => 'Modifier le produit du poste',
            ...$result['data'],
        ]);
    }

    public function update(Request $request, int $id)
    {
        $result = $this->service->update($request, $id);

        return to_route('post-produits.index')->with('success', $result['message']);
    }

    public function destroy(int $id)
    {
        $result = $this->service->delete($id);

        return to_route('post-produits.index')->with('success', $result['message']);
    }
}
