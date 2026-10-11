<?php

namespace App\Http\Controllers;

use App\Models\Carte;
use App\Models\Vehicule;
use App\Services\CarteService;
use Illuminate\Http\Request;
use Mpdf\Mpdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;


class CarteController extends Controller
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
    public function show(Carte $carte)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Carte $carte)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Carte $carte)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Carte $carte)
    {
        //
    }

  

    public function imprimerCarte(int $id = 1)
    {
        $status = $this->service::print($id);
        if (is_array($this->service::print($id))){
            return back()->with('error',$status['error']);
        }
        return $this->service::print($id);
    }


}
