<?php

namespace App\Services;

use App\Contracts\CarteServiceInterface;
use App\Models\CarteRose;
use App\Models\Vehicule;
use App\Support\CurrentPostTravail;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class CarteService implements CarteServiceInterface
{
    public function edit(int $vehiculeId, int $carteRoseId): array
    {
        $query = Vehicule::query();
        if ($postId = CurrentPostTravail::id()) {
            $query->where('post_travail_id', $postId);
        }
        $vehicule = $query->findOrFail($vehiculeId);
        $carteRose = $vehicule->carteRoses()->findOrFail($carteRoseId);

        return [
            'message' => 'Carte rose chargée.',
            'data' => compact('vehicule', 'carteRose'),
        ];
    }

    public function update(Request $request, int $vehiculeId, int $carteRoseId): array
    {
        $query = Vehicule::query();
        if ($postId = CurrentPostTravail::id()) {
            $query->where('post_travail_id', $postId);
        }
        $vehicule = $query->findOrFail($vehiculeId);
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

        return [
            'message' => 'Carte rose mise à jour. Les autres dossiers n’ont pas été modifiés.',
            'data' => ['vehicule' => $vehicule, 'carteRose' => $carteRose],
        ];
    }

    public static function print(int $id): array
    {
        $vehicule = VehiculesService::show($id)['data'];

        if (! $vehicule) {
            return [
                'message' => 'Nous avons du mal à trouver ce véhicule.',
                'data' => null,
            ];
        }

        $proprietaire = $vehicule->proprietaire;
        $codeQR = [
            'matricule' => $vehicule->immatriculation,
            'chassis' => $vehicule->chassis,
            'moteur' => $vehicule->moteur,
            'marque' => $vehicule->marque,
            'type' => $vehicule->type,
            'puissance' => $vehicule->puissance,
            'couleur' => $vehicule->couleur,
            'usage' => $vehicule->usage,
            //'cartinspection' => $vehicule->cartinspection,
            //'Annee circulation' => $vehicule->an_circulation,
            'proprietaire' => $proprietaire?->nom_complet ?? $proprietaire?->raison_sociale,
            //'Adresse' => $proprietaire?->adresse_physique,
        ];

        $createAt = Carbon::parse($vehicule->created_at);
        $ordre = VehiculesService::all()['data']->total();

        $vehicule->DGRK = 'DGRK/'.str_pad($ordre, 4, '0', STR_PAD_LEFT)
            .$createAt->format('d/m/Y');
        $vehicule->date = $createAt->format('d/m/Y');

        $pdf = Pdf::loadView('carte.rose', [
            'vehicule' => $vehicule,
            'qrCode' => self::genereQR($codeQR),
        ]);

        $pdf->setPaper([0, 0, 242.65, 153.01]);
        $pdf->setOptions([
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true,
            'defaultFont' => 'DejaVu Sans',
        ]);

        return [
            'message' => 'Carte rose générée.',
            'data' => $pdf->output(),
        ];
    }

    public static function genereQR(array $contenu)
    {
        return 'data:image/png;base64,'.base64_encode(
            QrCode::format('png')
                ->size(300)
                ->margin(1)
                ->generate(json_encode($contenu))
        );
    }
}