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
    public function edit(CarteRose $carteRose)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CarteRose $carteRose)
    {
        //
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

        if (is_array($result)) {
            return back()->with('error', $result['error']);
        }

        return $result;
    }
}
