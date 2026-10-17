<?php

namespace App\Services;

use App\Models\Plaque;
use App\Models\Vehicule;
use App\Support\CurrentPostTravail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class PlaqueService
{
    public function show(Plaque $plaque): array
    {
        return [
            'message' => 'Plaque chargée.',
            'data' => ['vehicule_id' => $plaque->vehicule_id],
        ];
    }

    public function edit(int $vehiculeId, int $plaqueId): array
    {
        $vehicule = $this->findVehicule($vehiculeId);
        $plaque = $vehicule->plaque()->whereKey($plaqueId)->firstOrFail();

        return ['message' => 'Plaque chargée.', 'data' => compact('vehicule', 'plaque')];
    }

    public function update(Request $request, int $vehiculeId, int $plaqueId): array
    {
        $vehicule = $this->findVehicule($vehiculeId);
        $plaque = $vehicule->plaque()->whereKey($plaqueId)->firstOrFail();
        $validated = $request->validate([
            'ville' => ['nullable', 'string', 'max:255'],
            'serie' => ['nullable', 'string', 'max:255'],
            'statut' => ['required', Rule::in(['disponible', 'attribuee', 'annulee'])],
            'date_attribution' => ['nullable', 'date'],
        ]);

        DB::transaction(fn () => $plaque->update($validated));

        return [
            'message' => 'Plaque mise à jour. Les autres dossiers n’ont pas été modifiés.',
            'data' => ['vehicule' => $vehicule],
        ];
    }

    private function findVehicule(int $id): Vehicule
    {
        $query = Vehicule::query();
        if ($postId = CurrentPostTravail::id()) {
            $query->where('post_travail_id', $postId);
        }

        return $query->with('plaque')->findOrFail($id);
    }
}
