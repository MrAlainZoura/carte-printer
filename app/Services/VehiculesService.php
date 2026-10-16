<?php

namespace App\Services;

use App\Contracts\VehiculeServiceInterface;
use App\Models\CarteRose;
use App\Models\HistoriqueProprietaireVehicule;
use App\Models\Plaque;
use App\Models\Propietaire;
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

            $plaque = $this->createPlaqueForVehicule($request, $post);
            $vehicule = Vehicule::create([...$data, 'plaque_id' => $plaque->id]);
            $plaque->update(['vehicule_id' => $vehicule->id]);
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
    private function lockRequiredStocks(PostTravail $post, array $requiredProducts = ['carte rose', 'plaque']): array
    {
        $stocks = [];
        $missing = [];

        foreach ($requiredProducts as $productName) {
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

    private function createPlaqueForVehicule(Request $request, PostTravail $post): Plaque
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
            'vehicule_id' => null,
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

        return view('vehicules.edit', compact('vehicule'));
    }

    public function update(int $id, Request $request)
    {
        $vehicule = Vehicule::with(['plaque', 'carteRoses'])->find($id);
        if (! $vehicule) {
            return back()->with('echec', 'Véhicule non trouvé');
        }

        $validated = $request->validate([
            'marque' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'couleur' => 'required|string|max:255',
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
        });

        return to_route('vehicules.show', $vehicule->id)
            ->with('success', 'Véhicule mis à jour avec succès.');
    }

    public function transferOwnerForm(int $id)
    {
        $vehicule = Vehicule::with('proprietaire')->findOrFail($id);
        $proprietaires = Propietaire::query()->orderBy('nom_complet')->orderBy('raison_sociale')->get();

        return view('vehicules.transfer-owner', compact('vehicule', 'proprietaires'));
    }

    public function transferOwner(int $id, Request $request)
    {
        $validated = $request->validate([
            'propietaire_id' => [
                'required',
                'integer',
                Rule::exists('propietaires', 'id')->whereNull('deleted_at'),
            ],
        ]);

        DB::transaction(function () use ($id, $validated) {
            $vehicule = Vehicule::query()->lockForUpdate()->findOrFail($id);
            $nouveauProprietaire = Propietaire::query()->findOrFail($validated['propietaire_id']);

            if ((int) $vehicule->propietaire_id === (int) $nouveauProprietaire->id) {
                throw ValidationException::withMessages([
                    'propietaire_id' => 'Ce propriétaire est déjà associé au véhicule.',
                ]);
            }

            $ancienProprietaire = Propietaire::withTrashed()->find($vehicule->propietaire_id);
            $nomProprietaire = fn (?Propietaire $proprietaire): string => $proprietaire
                ? ($proprietaire->nom_complet ?: $proprietaire->raison_sociale ?: 'Propriétaire #'.$proprietaire->id)
                : 'Propriétaire inconnu';

            HistoriqueProprietaireVehicule::create([
                'vehicule_id' => $vehicule->id,
                'ancien_proprietaire_id' => $ancienProprietaire?->id,
                'nouveau_proprietaire_id' => $nouveauProprietaire->id,
                'ancien_proprietaire' => $nomProprietaire($ancienProprietaire),
                'nouveau_proprietaire' => $nomProprietaire($nouveauProprietaire),
                'user_id' => Auth::id(),
                'transfere_le' => now(),
            ]);

            $vehicule->update(['propietaire_id' => $nouveauProprietaire->id]);
        });

        return to_route('vehicules.show', $id)->with('success', 'Propriétaire du véhicule modifié. Le transfert a été ajouté à l’historique.');
    }

    public function ownerHistory(int $id)
    {
        $vehicule = Vehicule::query()->findOrFail($id);
        $historique = $vehicule->historiqueProprietaires()
            ->with(['ancienProprietaire', 'nouveauProprietaire', 'utilisateur'])
            ->latest('transfere_le')
            ->paginate(15);

        return view('vehicules.owner-history', compact('vehicule', 'historique'));
    }

    public function changePlaqueForm(int $id)
    {
        $vehicule = Vehicule::with('plaque')->findOrFail($id);

        return view('vehicules.change-plaque', compact('vehicule'));
    }

    public function changePlaque(int $id, Request $request)
    {
        $request->merge(['code' => trim((string) $request->input('code'))]);
        $validated = $request->validate([
            'code' => ['required', 'string', 'max:255', Rule::unique('plaques', 'code')],
            'ville' => ['nullable', 'string', 'max:255'],
            'serie' => ['nullable', 'string', 'max:255'],
        ], [
            'code.unique' => 'Cette plaque est déjà attribuée à un véhicule.',
        ]);

        DB::transaction(function () use ($id, $validated) {
            $vehicule = Vehicule::query()->lockForUpdate()->with('plaque')->findOrFail($id);
            $anciennePlaque = $vehicule->plaque;
            $post = PostTravail::query()->findOrFail($vehicule->post_travail_id);
            $stock = $this->lockRequiredStocks($post, ['plaque'])['plaque'];

            if (Plaque::query()->where('code', $validated['code'])->exists()) {
                throw ValidationException::withMessages([
                    'code' => 'Cette plaque est déjà attribuée à un véhicule.',
                ]);
            }

            $nouvellePlaque = Plaque::create([
                'post_travail_id' => $post->id,
                'code' => $validated['code'],
                'ville' => $validated['ville'] ?? $post->ville,
                'serie' => $validated['serie'] ?? null,
                'statut' => 'attribuee',
                'vehicule_id' => null,
                'date_attribution' => now()->toDateString(),
            ]);

            $vehicule->update([
                'plaque_id' => $nouvellePlaque->id,
                'immatriculation' => $nouvellePlaque->code,
            ]);
            $nouvellePlaque->update(['vehicule_id' => $vehicule->id]);
            $anciennePlaque?->update(['statut' => 'annulee']);
            $stock->decrement('quantite');
        });

        return to_route('vehicules.show', $id)->with('success', 'La plaque du véhicule a été remplacée.');
    }
}
