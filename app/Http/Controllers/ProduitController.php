<?php

namespace App\Http\Controllers;

use App\Services\ProduitService;
use Illuminate\Http\Request;

class ProduitController extends Controller
{
    public function __construct(private readonly ProduitService $service)
    {
    }

    public function index(Request $request)
    {
        $result = $this->service->index($request);

        return view('produits.index', $result['data']);
    }

    public function create()
    {
        $result = $this->service->formData();

        return view('produits.form', [
            'title' => 'Créer un produit',
            ...$result['data'],
        ]);
    }

    public function store(Request $request)
    {
        $result = $this->service->store($request);

        return to_route('produits.index')->with('success', $result['message']);
    }

    public function show(int $id)
    {
        $result = $this->service->show($id);

        return view('produits.show', $result['data']);
    }

    public function edit(int $id)
    {
        $result = $this->service->formData($id);

        return view('produits.form', [
            'title' => 'Modifier le produit',
            ...$result['data'],
        ]);
    }

    public function update(Request $request, int $id)
    {
        $result = $this->service->update($request, $id);

        return to_route('produits.index')->with('success', $result['message']);
    }

    public function destroy(int $id)
    {
        $result = $this->service->delete($id);

        return to_route('produits.index')->with('success', $result['message']);
    }
}
