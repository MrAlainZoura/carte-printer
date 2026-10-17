<?php

namespace App\Http\Controllers;

use App\Models\CarteRose;
use App\Services\CarteService;
use Illuminate\Http\Request;

class CarteRoseController extends Controller
{
    protected CarteService $service;

    public function __construct(CarteService $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(CarteRose $carteRose)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $vehiculeId, int $carteRoseId)
    {
        $result = $this->service->edit($vehiculeId, $carteRoseId);
        $vehicule = $result['data']['vehicule'];
        $carteRose = $result['data']['carteRose'];

        return view('carte-roses.form', compact('vehicule', 'carteRose'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $vehiculeId, int $carteRoseId)
    {
        $result = $this->service->update($request, $vehiculeId, $carteRoseId);

        return to_route('vehicules.show', $result['data']['vehicule'])
            ->with('success', $result['message']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CarteRose $carteRose)
    {
        //
    }

    public function imprimerCarte(int $id = 1)
    {
        $result = $this->service::print($id);

        if ($result['data'] === null) {
            return back()->with('error', $result['message']);
        }

        return response($result['data'], 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="carte.pdf"',
        ]);
    }

}
