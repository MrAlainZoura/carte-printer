<?php

namespace App\Services;

use App\Contracts\CarteServiceInterface;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Mpdf\Mpdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class CarteService implements CarteServiceInterface
{
    static public function print(int $id)
    {
        $vehicule = VehiculesService::show($id)['data'];
        
        if(!$vehicule){
            return [
                'status'=>false,
                'error'=>"Nous avons du mal à trouver ce véhicule"
            ];
        }
        $codeQR = [
            'matricule' =>$vehicule->immatriculation,
            'chassis'   =>$vehicule->chassis,
            'moteur'    =>$vehicule->moteur,
            'marque'    =>$vehicule->marque,
            'type'      =>$vehicule->type,
            'puissance' => $vehicule->puissance,
            'couleur'   =>$vehicule->couleur,
            'usage'     =>$vehicule->usage,
            'cartinspection'    =>$vehicule->cartinspection,
            'Annee circulation' =>$vehicule->an_circulation,
            'proprietaire'      =>$vehicule->proprietaire->nom_complet ?? $vehicule->proprietaire->raison_social,
            'Adresse'           =>$vehicule->proprietaire->adresse_physique
        ];
        $mpdf = new Mpdf([
            'format' => [85.6, 53.98], // Format CR80 en mm
            'margin_left' => 0,
            'margin_right' => 0,
            'margin_top' => 0,
            'margin_bottom' => 0,
        ]);
        $create_at =Carbon::parse($vehicule->created_at);
        $ordre = count(VehiculesService::all()[1]);
        $DGRK = 'DGRK/'.str_pad($ordre, 4, '0', STR_PAD_LEFT).$create_at->format('d/m/Y');
        $vehicule->DGRK = $DGRK;
        $vehicule->date = $create_at->format('d/m/Y');
        $qrCode = self::genereQR($codeQR);
        $html = view('carte.vehicule', compact('vehicule', 'qrCode'))->render();

        $mpdf->WriteHTML($html);

        return response(
            $mpdf->Output('', 'S'),
            200,
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="carte.pdf"'
            ]
        );

    }
    static public function genereQR(array $contenu){
            $contenu = json_encode($contenu);

            return 'data:image/png;base64,' . base64_encode(
                QrCode::format('png')
                    ->size(300)
                    ->margin(1)
                    ->generate($contenu)
            );
    }
}