<?php

namespace App\Services;

use App\Contracts\CarteServiceInterface;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class CarteService implements CarteServiceInterface
{
    public static function print(int $id)
    {
        $vehicule = VehiculesService::show($id)['data'];

        if (! $vehicule) {
            return [
                'status' => false,
                'error' => 'Nous avons du mal à trouver ce véhicule',
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
            'cartinspection' => $vehicule->cartinspection,
            'Annee circulation' => $vehicule->an_circulation,
            'proprietaire' => $proprietaire?->nom_complet ?? $proprietaire?->raison_sociale,
            'Adresse' => $proprietaire?->adresse_physique,
        ];

        $createAt = Carbon::parse($vehicule->created_at);
        $ordre = count(VehiculesService::all()[1]);

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

        return response(
            $pdf->output(),
            200,
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="carte.pdf"',
            ]
        );
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