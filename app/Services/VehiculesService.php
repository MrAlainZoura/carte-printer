<?php

namespace App\Services;

use App\Contracts\VehiculeServiceInterface;
use App\Models\PostProduit;
use App\Models\Propietaire;
use App\Models\Vehicule;
use App\Services\ProprietaireService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Tymon\JWTAuth\Facades\JWTAuth;

class VehiculesService implements VehiculeServiceInterface
{
    

    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'marque'            => 'required|string|max:255',
            'type'              => 'required|string|max:255',
            'couleur'           => 'required|string|max:255',
            'immatriculation'   => 'required|string|max:255',
            'usage'             => 'required|string|max:255',
            'etat'              => 'required|string|max:255',
            'an_fabrication'    => 'required|string|max:255',
            'an_circulation'    => 'required|string|max:255',
            'moteur'            => 'required|string|max:255',
            'puissance'         => 'required|string|max:255',
            'chassis'           => 'required|string|max:255',
            'cartinspection'    => 'required|string|max:255',

            'creer_proprietaire'        => 'required|in:0,1',
            'nouveau_proprietaire'      => 'required_if:creer_proprietaire,1|array',
            ]);

        if($request->creer_proprietaire == true){
            //dd('next', $request->nouveau_proprietaire);
                $request->validate([
                    'nouveau_proprietaire.type' => [
                        'required',
                        'string',
                        'max:255',
                        Rule::in([
                            'Entreprise',
                            'Personne Physique',
                            'Organisation',
                            'Autres',
                        ]),
                    ],

                    'nouveau_proprietaire.adresse_physique'  => 'required|string|max:255',
                    'nouveau_proprietaire.telephone'         => 'required|string|max:255',

                    'nouveau_proprietaire.impot'             => [
                        Rule::requiredIf(
                            $request->input('nouveau_proprietaire.type') !== 'Personne Physique'
                        ),
                        'string',
                        'max:255',
                    ],

                    'nouveau_proprietaire.raison_sociale' => [
                        Rule::requiredIf(
                            $request->input('nouveau_proprietaire.type') !== 'Personne Physique'
                        ),
                        'string',
                        'max:255',
                    ],

                    'nouveau_proprietaire.nom_complet' => [
                        Rule::requiredIf(
                            $request->input('nouveau_proprietaire.type') === 'Personne Physique'
                        ),
                        'string',
                        'max:255',
                    ],
                ]);
            $tel = $request->input('nouveau_proprietaire.telephone');
            $checkProprio = ProprietaireService::getByTel($tel);
            $dataNewProprietaire = $request->only(['nouveau_proprietaire']);

            //dd($dataNewProprietaire["nouveau_proprietaire"], 'chien');

            $requestProprietaire = new Request(
                $dataNewProprietaire['nouveau_proprietaire']
            );

            if($checkProprio != null){
                $proprietaireId = $checkProprio->id;
            }else{
                $saveProprietaire = ProprietaireService::store($requestProprietaire);
                $proprietaireId = $saveProprietaire['data']->id;
            }
        }
        

        $proprietaire_id = $proprietaireId ?? null;

        $data = $request->only([
            'propietaire_id',
            'marque',
            'type',
            'couleur',
            'immatriculation',
            'usage',
            'etat',
            'an_fabrication',
            'an_circulation',
            'moteur',
            'puissance',
            'chassis',
            'cartinspection'
        ]);
        $data['propietaire_id'] = $data['propietaire_id'] ?? $proprietaire_id;
        $data['user_id']=Auth::user()->id;
        $data['post_travail_id']= 1;
        
        $vehicule = Vehicule::create($data);

        if($vehicule){
            PostProduit::query()->decrement('quantite', 1);
            return [
                'message'   => 'Véhicule créé.',
                'data'  => $vehicule
            ];
        }
    }

    public function create()
    {
        $proprietaires = ProprietaireService::index()['data'];
        return view('vehicules.create', compact('proprietaires'));
    }

    static public function show(int $id):array
    {
        $vehicule = Vehicule::with('proprietaire')->find($id);
        if(!$vehicule) {
            return [
                'message' => 'Véhicule non trouvé',
                'data' => null
            ];
        }
        return [
                'message' => 'Véhicule trouvé',
                'data' => $vehicule
        ];    
    }

    static public function all():array
    {
        //dd(Vehicule::with('proprietaire')->first());
        $vehicules = Vehicule::with('proprietaire')->latest();
        return [$vehicules->paginate(10),$vehicules->get() ]; // view('vehicules.index', compact('vehicules'));
    }
    public function delete(int $id)
    {
        $vehicule = Vehicule::find($id);
        if(!$vehicule) {
            return [
                'message' => 'Véhicule non trouvé',
                'data' => null
            ];
        }
        $vehicule->delete();
        return [
            'message' => 'Véhicule supprimé avec succès',
            'data' => null
        ];
    }

    public function edit(int $id)
    {
        $vehicule = Vehicule::find($id);
        if(!$vehicule) {
            return [
                'message' => 'Véhicule non trouvé',
                'data' => null
            ];
        }
        $proprietaires = ProprietaireService::index()['data'];
        return view('vehicules.edit', compact('vehicule', 'proprietaires'));
    }
    public function update(int $id, Request $data)
    {
        $vehicule = Vehicule::find($id);
        if(!$vehicule) {
            return [
                'message' => 'Véhicule non trouvé',
                'data' => null
            ];
        }
        $vehicule->update($data->only([
            'propietaire_id',
            'marque',
            'type',
            'couleur',
            'immatriculation',
            'usage',
            'etat',
            'an_fabrication',
            'an_circulation',
            'moteur',
            'puissance',
            'chassis',
            'cartinspection'
        ]));
        return [
            'message' => 'Véhicule mis à jour avec succès',
            'data' => $vehicule
        ];
    }
}