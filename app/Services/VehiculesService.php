<?php

namespace App\Services;

use App\Contracts\VehiculeServiceInterface;
use App\Models\CarteRose;
use App\Models\Plaque;
use App\Models\PostProduit;
use App\Models\PostTravail;
use App\Models\Vehicule;
use App\Support\CurrentPostTravail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class VehiculesService implements VehiculeServiceInterface
{
    private const NON_RENSEIGNE = 'Non renseigne';

    public function store(Request $request)
    {
        $request->merge([
            'creer_proprietaire' => $request->boolean('creer_proprietaire') ? '1' : '0',
            'immatriculation' => trim((string) $request->input('immatriculation')),
        ]);

        $request->validate([
            'marque' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'couleur' => 'required|string|max:255',
            'immatriculation' => [
                'required',
                'string',
                'max:255',
                Rule::unique('plaques', 'code'),
            ],
            'usage' => 'required|string|max:255',
            'etat' => 'nullable|string|max:255',
            'an_fabrication' => 'nullable|string|max:255',
            'an_circulation' => 'nullable|string|max:255',
            'moteur' => 'nullable|string|max:255',
            'puissance' => 'nullable|string|max:255',
            'chassis' => 'nullable|string|max:255',
            'cartinspection' => 'nullable|string|max:255',
            'carte_numero' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('carte_roses', 'numero'),
            ],
            'creer_proprietaire' => 'required|in:0,1',
            'propietaire_id' => 'required_if:creer_proprietaire,0|nullable|exists:propietaires,id',
            'nouveau_proprietaire' => 'required_if:creer_proprietaire,1|nullable|array',
        ], [
            'immatriculation.unique' => 'Cette carte est déjà occupée.',
        ]);

        $proprietaireId = $request->input('propietaire_id');
        $nouveauProprietaire = null;

        if ($request->creer_proprietaire === '1') {
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
                'nouveau_proprietaire.adresse_physique' => 'required|string|max:255',
                'nouveau_proprietaire.telephone' => 'required|string|max:255',
                'nouveau_proprietaire.impot' => [
                    Rule::requiredIf(
                        $request->input('nouveau_proprietaire.type') !== 'Personne Physique'
                    ),
                    'nullable',
                    'string',
                    'max:255',
                ],
                'nouveau_proprietaire.raison_sociale' => [
                    Rule::requiredIf(
                        $request->input('nouveau_proprietaire.type') !== 'Personne Physique'
                    ),
                    'nullable',
                    'string',
                    'max:255',
                ],
                'nouveau_proprietaire.nom_complet' => [
                    Rule::requiredIf(
                        $request->input('nouveau_proprietaire.type') === 'Personne Physique'
                    ),
                    'nullable',
                    'string',
                    'max:255',
                ],
            ]);

            $tel = $request->input('nouveau_proprietaire.telephone');
            $checkProprio = ProprietaireService::getByTel($tel);

            if ($checkProprio != null) {
                $proprietaireId = $checkProprio->id;
            } else {
                $nouveauProprietaire = $request->input('nouveau_proprietaire');
            }
        }

        $postId = CurrentPostTravail::id()
            ?? Auth::user()->defaultPostTravail()?->id;

        abort_unless($postId, 422, 'Aucun poste de travail sélectionné.');

        $post = PostTravail::query()->findOrFail($postId);

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
            'cartinspection',
        ]);
        $data['propietaire_id'] = $proprietaireId;
        $data['post_travail_id'] = $post->id;

        $vehicule = DB::transaction(function () use ($request, $data, $post, $nouveauProprietaire) {
            $this->assertPlaqueAvailable($request);
            $stocks = $this->lockRequiredStocks($post);

            if ($nouveauProprietaire !== null) {
                $requestProprietaire = new Request($nouveauProprietaire);
                $saveProprietaire = ProprietaireService::store($requestProprietaire);
                $data['propietaire_id'] = $saveProprietaire['data']->id;
            }

            $vehicule = Vehicule::create($data);

            $this->createPlaqueForVehicule($vehicule, $request, $post);
            $this->createCarteRoseForVehicule($vehicule, $request, $post);

            $stocks['plaque']->decrement('quantite');
            $stocks['carte rose']->decrement('quantite');

            return $vehicule->load(['proprietaire', 'plaque', 'carteRoses', 'postTravail']);
        });

        return [
            'message' => 'Véhicule, plaque et carte rose créés.',
            'data' => $vehicule,
        ];
    }

    /**
     * @return array<string, PostProduit>
     */
    private function lockRequiredStocks(PostTravail $post): array
    {
        $stocks = [];
        $missing = [];

        foreach (['carte rose', 'plaque'] as $productName) {
            $stock = PostProduit::query()
                ->where('post_travail_id', $post->id)
                ->whereHas('produit', fn ($query) => $query
                    ->whereRaw('LOWER(TRIM(libele)) = ?', [$productName]))
                ->lockForUpdate()
                ->first();

            if (! $stock || (float) $stock->quantite < 1) {
                $missing[] = $productName;

                continue;
            }

            $stocks[$productName] = $stock;
        }

        if ($missing !== []) {
            throw ValidationException::withMessages([
                'stock' => 'Stock indisponible pour : '.implode(' et ', $missing)
                    .'. Veuillez approvisionner ces produits avant d’enregistrer le véhicule.',
            ]);
        }

        return $stocks;
    }

    private function assertPlaqueAvailable(Request $request): void
    {
        $code = $this->valueOrDefault(
            $request->input('immatriculation') ?: $request->input('plaque_code')
        );

        if (Plaque::query()->where('code', $code)->exists()) {
            throw ValidationException::withMessages([
                'immatriculation' => 'Cette carte est déjà occupée.',
            ]);
        }
    }

    private function createPlaqueForVehicule(Vehicule $vehicule, Request $request, PostTravail $post): Plaque
    {
        $code = $this->valueOrDefault(
            $request->input('immatriculation') ?: $request->input('plaque_code')
        );

        return Plaque::create([
            'post_travail_id' => $post->id,
            'code' => $code,
            'ville' => $this->nullableFromRequestOrPost(
                $request->input('plaque_ville'),
                $post->ville
            ),
            'serie' => $this->nullableFromRequestOrPost($request->input('plaque_serie')),
            'statut' => $this->valueOrDefault($request->input('plaque_statut'), 'attribuee'),
            'vehicule_id' => $vehicule->id,
            'date_attribution' => now()->toDateString(),
        ]);
    }

    private function createCarteRoseForVehicule(Vehicule $vehicule, Request $request, PostTravail $post): CarteRose
    {
        $numero = trim((string) $request->input('carte_numero'));
        if ($numero === '') {
            $numero = $this->generateCarteNumero($vehicule);
        }

        return CarteRose::create([
            'post_travail_id' => $post->id,
            'vehicule_id' => $vehicule->id,
            'user_id' => Auth::id(),
            'libele' => $this->valueOrDefault($request->input('carte_libele'), 'Carte Rose'),
            'numero' => $numero,
            'date_octroi' => now()->toDateString(),
            'date_expiration' => null,
            'statut' => $this->valueOrDefault($request->input('carte_statut'), 'active'),
            'qr_token' => (string) Str::uuid(),
            'observations' => $this->nullableFromRequestOrPost($request->input('carte_observations')),
        ]);
    }

    private function generateCarteNumero(Vehicule $vehicule): string
    {
        $ordre = CarteRose::query()->count() + 1;

        do {
            $numero = 'DGRK/'.str_pad((string) $ordre, 4, '0', STR_PAD_LEFT)
                .'/'.$vehicule->id
                .'/'.now()->format('dmY');
            $ordre++;
        } while (CarteRose::query()->where('numero', $numero)->exists());

        return $numero;
    }

    private function valueOrDefault(?string $value, ?string $fallback = null): string
    {
        $value = is_string($value) ? trim($value) : '';

        if ($value !== '') {
            return $value;
        }

        $fallback = is_string($fallback) ? trim($fallback) : '';

        return $fallback !== '' ? $fallback : self::NON_RENSEIGNE;
    }

    private function nullableFromRequestOrPost(?string $requestValue, ?string $postValue = null): ?string
    {
        $requestValue = is_string($requestValue) ? trim($requestValue) : '';
        if ($requestValue !== '') {
            return $requestValue;
        }

        $postValue = is_string($postValue) ? trim($postValue) : '';

        return $postValue !== '' ? $postValue : null;
    }

    public function create()
    {
        $proprietaires = ProprietaireService::index()['data'];

        return view('vehicules.create', compact('proprietaires'));
    }

    public static function show(int $id): array
    {
        $vehicule = Vehicule::with(['proprietaire', 'plaque', 'carteRoses', 'postTravail'])
            ->find($id);

        if (! $vehicule) {
            return [
                'message' => 'Véhicule non trouvé',
                'data' => null,
            ];
        }

        return [
            'message' => 'Véhicule trouvé',
            'data' => $vehicule,
        ];
    }

    public static function all(): array
    {
        $base = Vehicule::with('proprietaire')->latest();

        if ($postId = CurrentPostTravail::id()) {
            $base->where('post_travail_id', $postId);
        }

        return [
            (clone $base)->paginate(10),
            (clone $base)->get(),
        ];
    }

    public function delete(int $id)
    {
        $vehicule = Vehicule::find($id);
        if (! $vehicule) {
            return [
                'message' => 'Véhicule non trouvé',
                'data' => null,
            ];
        }
        $vehicule->delete();

        return [
            'message' => 'Véhicule supprimé avec succès',
            'data' => null,
        ];
    }

    public function edit(int $id)
    {
        $vehicule = Vehicule::with(['proprietaire', 'plaque', 'carteRoses'])->find($id);
        if (! $vehicule) {
            return back()->with('echec', 'Véhicule non trouvé');
        }

        $proprietaires = ProprietaireService::index()['data'];

        return view('vehicules.edit', compact('vehicule', 'proprietaires'));
    }

    public function update(int $id, Request $request)
    {
        $vehicule = Vehicule::with(['plaque', 'carteRoses'])->find($id);
        if (! $vehicule) {
            return back()->with('echec', 'Véhicule non trouvé');
        }

        $validated = $request->validate([
            'propietaire_id' => 'required|exists:propietaires,id',
            'marque' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'couleur' => 'required|string|max:255',
            'immatriculation' => 'required|string|max:255',
            'usage' => 'required|string|max:255',
            'etat' => 'nullable|string|max:255',
            'an_fabrication' => 'nullable|string|max:255',
            'an_circulation' => 'nullable|string|max:255',
            'moteur' => 'nullable|string|max:255',
            'puissance' => 'nullable|string|max:255',
            'chassis' => 'nullable|string|max:255',
            'cartinspection' => 'nullable|string|max:255',
        ]);

        DB::transaction(function () use ($vehicule, $validated) {
            $vehicule->update($validated);

            if ($vehicule->plaque) {
                $code = $this->valueOrDefault($validated['immatriculation']);
                if (
                    Plaque::query()
                        ->where('code', $code)
                        ->where('id', '!=', $vehicule->plaque->id)
                        ->exists()
                ) {
                    $code = $code.'-'.$vehicule->id;
                }

                $vehicule->plaque->update([
                    'code' => $code,
                ]);
            }
        });

        return to_route('vehicules.show', $vehicule->id)
            ->with('success', 'Véhicule mis à jour avec succès.');
    }
}
