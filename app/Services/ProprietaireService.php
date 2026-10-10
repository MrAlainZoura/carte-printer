<?php

namespace App\Services;

use App\Contracts\ProprietaireServiceInterface;
use App\Models\Propietaire;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class ProprietaireService implements ProprietaireServiceInterface
{
    

    static public function store(Request $request)
    {
        //return response()->json([$request->all()]);
        $request->validate([
            'nom_complet'       => 'string|max:255',
            'raison_sociale'    => 'string|max:255',
            'adresse_physique'  => 'required|string|max:255',
            'impot'             => 'string|max:255',
            // Téléphone obligatoire, format international
            'telephone'         => [
                    'required',
                    'string',
                    'unique:propietaires',
                    'regex:/^\+[1-9][0-9]{7,14}$/'
                ],
        ]);

        $proprietaire = Propietaire::create([
            'nom_complet'       => $request->nom_complet,
            'raison_sociale'    => $request->raison_sociale,
            'adresse_physique'  => $request->adresse_physique,
            'telephone'         => $request->telephone,
            'impot'             => $request->impot,
        ]);

        return [
            'message'   => 'Utilisateur créé. Vérifiez votre email.',
            'data'  => $proprietaire
        ];
    }

    public function create()
    {
        //
    }

    public function show(int $id)
    {
        $proprietaire = Propietaire::find($id);
        if(!$proprietaire) {
            return [
                'message' => 'Propriétaire non trouvé',
                'data' => null
            ];
        }
        return [
                'message' => 'Propriétaire trouvé',
                'data' => $proprietaire
        ];    
    }

    static public function index()
    {
        $proprietaire = Propietaire::latest()->get();
        return [
            'message' => 'Liste des propriétaires', 
            "data" => $proprietaire
        ];
    }
    public function delete(int $id)
    {
        $proprietaire = Propietaire::find($id);
        if(!$proprietaire) {
            return [
                'message' => 'Propriétaire non trouvé',
                'data' => null
            ];
        }
        $proprietaire->delete();
        return [
            'message' => 'Propriétaire supprimé avec succès',
            'data' => null
        ];
    }

    public function edit(int $id)
    {
        $proprietaire = Propietaire::find($id);
        if(!$proprietaire) {
            return [
                'message' => 'Propriétaire non trouvé',
                'data' => null
            ];
        }
        return [
            'message' => 'Propriétaire trouvé',
            'data' => $proprietaire
        ];
    }
    public function update(int $id, Request $request)
    {
        $request->validate([
            'nom_complet'       => 'string|max:255',
            'raison_sociale'    => 'string|max:255',
            'adresse_physique'  => 'required|string|max:255',
            'impot'             => 'string|max:255',
            // Téléphone obligatoire, format international
            'telephone'         => [
                    'required',
                    'string',
                    'regex:/^\+[1-9][0-9]{7,14}$/'
                ],
        ]);
        $proprietaire = Propietaire::find($id);
        if(!$proprietaire) {
            return [
                'message' => 'Propriétaire non trouvé',
                'data' => null
            ];
        }
        $proprietaire->update($request->only(['nom_complet', 'raison_sociale', 'adresse_physique', 'telephone', 'impot']));
        return [
            'message' => 'Propriétaire mis à jour avec succès',
            'data' => $proprietaire
        ];
    }
}