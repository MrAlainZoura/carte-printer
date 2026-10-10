<?php

namespace App\Http\Controllers;

use App\Models\Propietaire;
use App\Models\Vehicule;
use Illuminate\Http\Request;
use App\Services\ProprietaireService;

class PropietaireController extends Controller
{
    protected ProprietaireService $service;

    public function __construct(ProprietaireService $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->service->index();
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
        return $this->service->store($request);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        return $this->service->show($id);
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
    public function update(int $id, Request $request)
    {
        return $this->service->update($id, $request);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Propietaire $propietaire)
    {
        return $this->service->delete($propietaire->id);
    }
}
