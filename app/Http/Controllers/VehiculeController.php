<?php

namespace App\Http\Controllers;

use App\Models\Vehicule;
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
        $vehicules = $this->service::all()[0];

        return view('vehicules.index', compact('vehicules'));
    }

    public function create()
    {
        return $this->service->create();
    }

    public function store(Request $request)
    {
        $vehicule = $this->service->store($request)['data'];

        return to_route('vehicules.show', $vehicule->id)
            ->with('success', 'Véhicule, plaque et carte rose enregistrés avec succès.');
    }

    public function show(int $id)
    {
        $vehicule = $this->service->show($id)['data'];
        if (! $vehicule) {
            return back()->with('echec', 'Impossible de trouvé ce véhicule');
        }

        return view('vehicules.show', compact('vehicule'));
    }

    public function edit(int $id)
    {
        return $this->service->edit($id);
    }

    public function update(Request $request, int $id)
    {
        return $this->service->update($id, $request);
    }

    public function destroy(int $id)
    {
        $vehicule = Vehicule::findOrFail($id);
        $this->service->delete($vehicule->id);

        return to_route('vehicules.index')->with('success', 'Véhicule supprimé avec succès.');
    }
}
