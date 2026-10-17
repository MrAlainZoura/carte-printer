<?php

namespace App\Http\Controllers;

use App\Services\VehiculesService;
use Illuminate\Http\Request;

class VehiculeController extends Controller
{
    protected VehiculesService $service;

    public function __construct(VehiculesService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $result = $this->service::all();
        $vehicules = $result['data'];

        return view('vehicules.index', compact('vehicules'));
    }

    public function create()
    {
        $result = $this->service->create();

        return view('vehicules.create', $result['data']);
    }

    public function store(Request $request)
    {
        $result = $this->service->store($request);
        $vehicule = $result['data'];

        return to_route('vehicules.show', $vehicule->id)
            ->with('success', $result['message']);
    }

    public function show(int $id)
    {
        $result = $this->service->show($id);
        $vehicule = $result['data'];
        if (! $vehicule) {
            abort(404, $result['message']);
        }

        return view('vehicules.show', compact('vehicule'));
    }

    public function edit(int $id)
    {
        $result = $this->service->edit($id);

        return view('vehicules.edit', ['vehicule' => $result['data']]);
    }

    public function update(Request $request, int $id)
    {
        $result = $this->service->update($id, $request);

        return to_route('vehicules.show', $id)->with('success', $result['message']);
    }

    public function transferOwnerForm(int $id)
    {
        $result = $this->service->transferOwnerForm($id);

        return view('vehicules.transfer-owner', $result['data']);
    }

    public function transferOwner(Request $request, int $id)
    {
        $result = $this->service->transferOwner($id, $request);

        return to_route('vehicules.show', $id)->with('success', $result['message']);
    }

    public function ownerHistory(int $id)
    {
        $result = $this->service->ownerHistory($id);

        return view('vehicules.owner-history', $result['data']);
    }

    public function changePlaqueForm(int $id)
    {
        $result = $this->service->changePlaqueForm($id);

        return view('vehicules.change-plaque', $result['data']);
    }

    public function changePlaque(Request $request, int $id)
    {
        $result = $this->service->changePlaque($id, $request);

        return to_route('vehicules.show', $id)->with('success', $result['message']);
    }

    public function destroy(int $id)
    {
        $result = $this->service->delete($id);

        return to_route('vehicules.index')->with('success', $result['message']);
    }
}
