<?php

namespace App\Http\Controllers;

use App\Models\CarteRose;
use App\Models\Vehicule;
use App\Services\CarteService;
use App\Support\CurrentPostTravail;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

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
        $vehicule = $this->findVehicule($vehiculeId);
        $carteRose = $vehicule->carteRoses()->findOrFail($carteRoseId);

        return view('carte-roses.form', compact('vehicule', 'carteRose'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $vehiculeId, int $carteRoseId)
    {
        $vehicule = $this->findVehicule($vehiculeId);
        $carteRose = $vehicule->carteRoses()->findOrFail($carteRoseId);

        $validated = $request->validate([
            'libele' => ['required', 'string', 'max:255'],
            'numero' => ['required', 'string', 'max:255', Rule::unique('carte_roses', 'numero')->ignore($carteRose->id)],
            'date_octroi' => ['nullable', 'date'],
            'date_expiration' => ['nullable', 'date', 'after_or_equal:date_octroi'],
            'statut' => ['required', Rule::in(['active', 'expiree', 'annulee', 'perdue'])],
            'observations' => ['nullable', 'string'],
        ]);

        $carteRose->update($validated);

        return to_route('vehicules.show', $vehicule)
            ->with('success', 'Carte rose mise à jour. Les autres dossiers n’ont pas été modifiés.');
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

    private function findVehicule(int $id): Vehicule
    {
        $query = Vehicule::query();

        if ($postId = CurrentPostTravail::id()) {
            $query->where('post_travail_id', $postId);
        }

        return $query->findOrFail($id);
    }
}
