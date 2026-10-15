<?php

namespace App\Http\Controllers;

use App\Models\Propietaire;
use Illuminate\Http\Request;

class PropietaireController extends Controller
{
    public function index(Request $request)
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

        return view('proprietaires.index', [
            'proprietaires' => $query->latest()->paginate(15)->withQueryString(),
        ]);
    }

    public function create()
    {
        return $this->form(new Propietaire(), 'Créer un propriétaire');
    }

    public function store(Request $request)
    {
        Propietaire::create($request->validate($this->rules()));

        return to_route('proprietaires.index')->with('success', 'Propriétaire créé avec succès.');
    }

    public function show(int $id)
    {
        $record = Propietaire::withCount('vehicules')->findOrFail($id);

        return view('proprietaires.show', compact('record'));
    }

    public function edit(int $id)
    {
        return $this->form(Propietaire::findOrFail($id), 'Modifier le propriétaire');
    }

    public function update(Request $request, int $id)
    {
        $record = Propietaire::findOrFail($id);
        $record->update($request->validate($this->rules()));

        return to_route('proprietaires.index')->with('success', 'Propriétaire modifié avec succès.');
    }

    public function destroy(int $id)
    {
        $proprietaire = Propietaire::findOrFail($id);
        if ($proprietaire->vehicules()->withTrashed()->exists()) {
            return to_route('proprietaires.index')
                ->with('error', 'Ce propriétaire ne peut pas être supprimé tant que des véhicules lui sont associés.');
        }

        $proprietaire->delete();

        return to_route('proprietaires.index')->with('success', 'Propriétaire supprimé avec succès.');
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

    private function form(Propietaire $record, string $title)
    {
        return view('proprietaires.form', [
            'title' => $title,
            'record' => $record,
        ]);
    }
}
