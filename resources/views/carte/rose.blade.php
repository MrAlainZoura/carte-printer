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
            top: 13mm;
            left: 0;
            width: 85.6mm;
            text-align: center;
            font-size: 7.5pt;
            font-weight: bold;
            /*background: #ee7373;*/
        }

        .owner-block {
            position: absolute;
            top: 19mm;
            left: 38mm;
            font-weight: bold;
            /*background: #7171e8;*/
        }

        .line0 {
            /*height: 5mm;*/
            /*line-height: 5mm;*/
            margin-bottom: 1mm;
            overflow: hidden;
            white-space: nowrap;
        }
        .line {
            padding-right: 1mm;
            margin-bottom: 1mm;
            white-space: normal;
            overflow-wrap: break-word;
        }

        .qr-block {
            position: absolute;
            top: 32.2mm;
            left: 65mm;
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
            top: 8mm;
            left: 42mm;
            width: 34mm;
            padding: 2;
            font-weight: bold;
        }

        .verso-signature {
            position: absolute;
            top: 41.3mm;
            left: 62mm;
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
        .pds{
            margin-top: 6mm;
        }
    </style>
</head>
<body>
    <div class="page recto">
        <div class="dgrk">{{ $vehicule->DGRK }}</div>

        <div class="owner-block">
            <div class="line">{{ $vehicule->proprietaire?->nom_complet ?? $vehicule->proprietaire?->raison_sociale ?? '-' }}</div>
            <div class="line">{{ $vehicule->proprietaire?->adresse_physique ?? '-' }}</div>
            <div class="line"></div>
            <div class="pds">
                <div class="line">{{ $vehicule->an_circulation }}</div>
                <div class="line">{{ $vehicule->usage }}</div>
                <div class="line">{{ $vehicule->immatriculation }}</div>
            </div>
        </div>

        <div class="qr-block">
            <img src="{{ $qrCode }}" alt="QR">
            <div class="date-octroi">{{ $vehicule->date }}</div>
        </div>
    </div>

    <div class="page verso">
        <div class="verso-fields">
            <div class="vline">{{ $vehicule->marque }}</div>
            <div class="vline">-</div>
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
