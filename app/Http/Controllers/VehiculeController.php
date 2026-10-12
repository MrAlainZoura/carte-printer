<?php

namespace App\Http\Controllers;

use App\Models\Vehicule;
use Illuminate\Http\Request;
use App\Services\VehiculesService;

class VehiculeController extends Controller
{
    protected VehiculesService $service;
    public function __construct(VehiculesService $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $vehicules =$this->service::all()[0];
        return  view('vehicules.index', compact('vehicules'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
       return $this->service->create();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $vehicule = $this->service->store($request)['data'];
        return to_route('vehicules.show', $vehicule->id);
        return to_route('vehicules.index')->with('success', 'Véhicule créé avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $vehicule = $this->service->show($id)['data'];
        if(!$vehicule){
            return back()->with('echec', "Impossible de trouvé ce véhicule");
        }
       return view('vehicules.show', compact('vehicule'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id)
    {
        return $this->service->edit($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        return $this->service->update($id, $request);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Vehicule $vehicule)
    {
        return $this->service->delete($vehicule->id);
    }
}
