<?php

namespace App\Http\Controllers;

use App\Models\Plaque;
use App\Models\Vehicule;
use App\Support\CurrentPostTravail;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PlaqueController extends Controller
{
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
    public function show(Plaque $plaque)
    {
        return redirect()->route('vehicules.show', $plaque->vehicule_id);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $vehiculeId, int $plaqueId)
    {
        $vehicule = $this->findVehicule($vehiculeId);
        $plaque = $vehicule->plaque()->findOrFail($plaqueId);

        return view('plaques.form', compact('vehicule', 'plaque'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $vehiculeId, int $plaqueId)
    {
        $vehicule = $this->findVehicule($vehiculeId);
        $plaque = $vehicule->plaque()->findOrFail($plaqueId);

        $validated = $request->validate([
            'code' => ['required', 'string', 'max:255', Rule::unique('plaques', 'code')->ignore($plaque->id)],
            'ville' => ['nullable', 'string', 'max:255'],
            'serie' => ['nullable', 'string', 'max:255'],
            'statut' => ['required', Rule::in(['disponible', 'attribuee', 'annulee'])],
            'date_attribution' => ['nullable', 'date'],
        ]);

        $plaque->update($validated);

        return to_route('vehicules.show', $vehicule)
            ->with('success', 'Plaque mise à jour. Les autres dossiers n’ont pas été modifiés.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Plaque $plaque)
    {
        //
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
