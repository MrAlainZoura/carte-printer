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

        html,
        body {
            width: 85.6mm;
            height: 53.98mm;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            font-size: 7.5pt;
            font-weight: bold;
            color: #000;
        }

        .page {
            position: relative;
            width: 85.6mm;
            height: 53.98mm;
            overflow: hidden;
        }

        .recto .dgrk {
            position: absolute;
            top: 15mm;
            left: 0;
            width: 85.6mm;
            text-align: center;
            font-size: 8pt;
            /*background: #ee7373;*/
        }

        .owner-block {
            position: absolute;
            top: 20mm;
            left: 38mm;
            /*background: #7171e8;*/
        }

        .line {
            height: 5mm;
            /*line-height: 5mm;*/
            overflow: hidden;
            white-space: nowrap;
        }

        .qr-block {
            position: absolute;
            top: 35mm;
            left: 60mm;
            width: 14mm;
            text-align: center;
            /*background: #e4eb7f;*/
        }

        .qr-block img {
            display: block;
            width: 19mm;
            height: 19mm;
        }

        .date-octroi {
            font-size: 6pt;
            font-weight: bold;
            text-align: center;
        }

        .verso {
            page-break-before: always;
        }

        .verso-fields {
            position: absolute;
            top: 7mm;
            left: 42mm;
            width: 22mm;
            padding: 2;
        }

        .verso-signature {
            position: absolute;
            top: 40mm;
            left: 60mm;
            width: 18mm;
            height: 9mm;
            /*background: #98c6d0;*/
        }

        .vline {
            /*height: 5mm;
            line-height: 3.2mm;*/
            overflow: hidden;
            white-space: nowrap;
        }
    </style>
</head>
<body>
    <div class="page recto">
        <div class="dgrk">{{ $vehicule->DGRK }}</div>

        <div class="owner-block">
            <div class="line">{{ $vehicule->proprietaire?->nom_complet ?? $vehicule->proprietaire?->raison_sociale ?? '-' }}</div>
            <div class="line">{{ $vehicule->proprietaire?->adresse_physique ?? 'Non renseigné' }}</div>
            <div class="line">{{ $vehicule->proprietaire?->impot ?? '' }}</div>
            <div class="line">{{ $vehicule->an_circulation }}</div>
            <div class="line">{{ $vehicule->usage }}</div>
            <div class="line">{{ $vehicule->immatriculation }}</div>
        </div>

        <div class="qr-block">
            <img src="{{ $qrCode }}" alt="QR">
            <div class="date-octroi">{{ $vehicule->date }}</div>
        </div>
    </div>

    <div class="page verso">
        <div class="verso-fields">
            <div class="vline">{{ $vehicule->marque }}</div>
            <div class="vline">{{ $vehicule->cartinspection }}</div>
            <div class="vline">{{ $vehicule->chassis ?? '-' }}</div>
            <div class="vline">{{ $vehicule->moteur }}</div>
            <div class="vline">{{ $vehicule->an_fabrication }}</div>
            <div class="vline">{{ $vehicule->couleur }}</div>
            <div class="vline">{{ $vehicule->puissance }}</div>
        </div>
        <div class="verso-signature">
            <img src="data:image/jpg;base64,{{base64_encode(file_get_contents(public_path('sign/signature.jpg'))) }}" 
            alt="logo" width="68" height="34" 
            style="width:23mm; height:9mm;">
        </div>
    </div>
</body>
</html>
