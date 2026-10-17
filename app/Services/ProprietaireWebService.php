<?php

namespace App\Services;

use App\Models\Propietaire;
use Illuminate\Http\Request;

class ProprietaireWebService
{
    public function index(Request $request): array
    {
        $query = Propietaire::withCount('vehicules');
        if ($search = $request->string('search')->trim()->toString()) {
            $query->where(fn ($builder) => $builder
                ->where('nom_complet', 'like', "%{$search}%")
                ->orWhere('raison_sociale', 'like', "%{$search}%")
                ->orWhere('telephone', 'like', "%{$search}%")
                ->orWhere('impot', 'like', "%{$search}%"));
        }
        if ($type = $request->string('type')->trim()->toString()) {
            $query->where('type', $type);
        }

        return ['message' => 'Liste des propriétaires chargée.', 'data' => [
            'proprietaires' => $query->latest()->paginate(15)->withQueryString(),
        ]];
    }

    public function formData(?int $id = null): array
    {
        return ['message' => 'Formulaire propriétaire chargé.', 'data' => [
            'record' => $id === null ? new Propietaire() : Propietaire::findOrFail($id),
        ]];
    }

    public function store(Request $request): array
    {
        $record = Propietaire::create($request->validate($this->rules()));

        return ['message' => 'Propriétaire créé avec succès.', 'data' => $record];
    }

    public function show(int $id): array
    {
        $record = Propietaire::withCount('vehicules')->findOrFail($id);

        return ['message' => 'Propriétaire chargé.', 'data' => compact('record')];
    }

    public function update(Request $request, int $id): array
    {
        $record = Propietaire::findOrFail($id);
        $record->update($request->validate($this->rules()));

        return ['message' => 'Propriétaire modifié avec succès.', 'data' => $record];
    }

    public function delete(int $id): array
    {
        $proprietaire = Propietaire::findOrFail($id);
        if ($proprietaire->vehicules()->withTrashed()->exists()) {
            return [
                'message' => 'Ce propriétaire ne peut pas être supprimé tant que des véhicules lui sont associés.',
                'data' => null,
                'deleted' => false,
            ];
        }

        $proprietaire->delete();

        return ['message' => 'Propriétaire supprimé avec succès.', 'data' => null, 'deleted' => true];
    }

    private function rules(): array
    {
        return [
            'type' => ['required', 'string', 'max:255'],
            'nom_complet' => ['nullable', 'string', 'max:255'],
            'raison_sociale' => ['nullable', 'string', 'max:255'],
            'adresse_physique' => ['nullable', 'string', 'max:255'],
            'telephone' => ['nullable', 'string', 'max:255'],
            'impot' => ['nullable', 'string', 'max:255'],
        ];
    }
}
