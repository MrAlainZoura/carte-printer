{{--
  Carte Rose CR80 — calée sur les aperçus WhatsApp (04.54.48 / 04.54.49).

  Recto (mm) :
    DGRK ≈ 15 | valeurs x≈42 | lignes 19/24/29/34/39/44 (pas 5)
    QR ≈ 72×31, 14×14 | date sous QR

  Verso (mm) :
    valeurs x≈42 | 1re ligne ≈12 | pas ≈3.2 | signature bas-droite ≈ y40+
--}}
@php
    $face = $face ?? 'recto';
@endphp
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Carte Rose</title>
    <style>
        @page {
            size: 85.6mm 53.98mm;
            margin: 0;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 7.5pt;
            font-weight: bold;
            color: #000;
        }

        table.card {
            width: 85.6mm;
            border-collapse: collapse;
        }

        table.card td {
            padding: 0;
            margin: 0;
            vertical-align: top;
            border: 0;
        }

        .dgrk {
            text-align: center;
            font-size: 7pt;
            font-weight: bold;
        }

        .line {
            height: 5mm;
            line-height: 5mm;
            overflow: hidden;
            white-space: nowrap;
        }

        .vline {
            height: 3.2mm;
            line-height: 3.2mm;
            overflow: hidden;
            white-space: nowrap;
        }

        .date-octroi {
            font-size: 6pt;
            font-weight: bold;
            text-align: center;
        }
    </style>
</head>
<body>

@if ($face === 'recto')
    <table class="card">
        {{-- Espace sous bandeau bleu → DGRK vers y≈15mm --}}
        <tr>
            <td colspan="3" class="dgrk" style="height:15mm; vertical-align:bottom; padding-bottom:1mm;">
                {{ $vehicule->DGRK }}
            </td>
        </tr>
        <tr>
            {{-- Libellés préimprimés jusqu'à ~38–40mm ; valeurs à x≈42mm --}}
            <td style="width:42mm;">&nbsp;</td>
            <td style="width:16mm;">
                <div class="line">{{ $vehicule->proprietaire?->nom_complet ?? $vehicule->proprietaire?->raison_sociale ?? '-' }}</div>
                <div class="line">{{ $vehicule->proprietaire?->adresse_physique ?? 'Non renseigné' }}</div>
                <div class="line">{{ $vehicule->proprietaire?->impot ?? '' }}</div>
                <div class="line">{{ $vehicule->an_circulation }}</div>
                <div class="line">{{ $vehicule->usage }}</div>
                <div class="line">{{ $vehicule->immatriculation }}</div>
            </td>
            <td style="width:27.6mm; text-align:center; padding-right:1mm;">
                {{-- QR à y≈31mm (après ~16mm depuis début des lignes à y≈16) --}}
                <div style="height:15mm; line-height:15mm; font-size:1pt;">&nbsp;</div>
                <img src="{{ $qrCode }}" alt="QR" width="53" height="53" style="width:14mm; height:14mm;"><br>
                <span class="date-octroi">{{ $vehicule->date }}</span>
            </td>
        </tr>
    </table>
@endif

@if ($face === 'verso')
    <table class="card">
        {{-- Sous « IDENTIFICATION MOTO » → 1re ligne ≈12mm --}}
        <tr>
            <td colspan="3" style="height:11mm;">&nbsp;</td>
        </tr>
        <tr>
            <td style="width:42mm;">&nbsp;</td>
            <td style="width:22mm;">
                <div class="vline">{{ $vehicule->marque }}</div>
                <div class="vline">{{ $vehicule->cartinspection }}</div>
                <div class="vline">{{ $vehicule->chassis ?? '-' }}</div>
                <div class="vline">{{ $vehicule->moteur }}</div>
                <div class="vline">{{ $vehicule->an_fabrication }}</div>
                <div class="vline">{{ $vehicule->couleur }}</div>
                <div class="vline">{{ $vehicule->puissance }}</div>
            </td>
            <td style="width:21.6mm;">&nbsp;</td>
        </tr>
        {{-- Zone Remarques préimprimée ; signature dans encadré blanc bas-droite --}}
        <tr>
            <td colspan="2" style="height:9mm;">&nbsp;</td>
            <td style="width:21.6mm; height:12mm; text-align:center; vertical-align:middle; padding-right:2mm;">
                <img src="{{ public_path('sign/signature.jpg') }}" alt="Signature" width="68" height="34" style="width:18mm; height:9mm;">
            </td>
        </tr>
    </table>
@endif

</body>
</html>
