<style>
    @page {
        size: 85.6mm 53.98mm;
        margin: 0;
    }

    * {
        box-sizing: border-box;
    }

    html,
    body {
        margin: 0;
        padding: 0;
        font-family: Arial, sans-serif;
    }

    .card {
        position: relative;
        width: 85.6mm;
        height: 53.98mm;
        overflow: hidden;
        background: transparent;
        color: #000;
        font-weight: bold;
    }

    
    table{
        width: 100%;
        height: 100%;
        /*background-color: #6fcbe7;*/
        border-collapse: collapse;

    }
    tr, td{
        /*border: 1px solid black;*/
    }

    .photo{
        position:absolute;
        left:3mm;
        top:15mm;
    }

    .photo img{
        width:22mm;
        height:28mm;
    }

    .qrjjj {
            position: absolute;
            right: 3mm;
            bottom: 3mm;
            width: 17mm;
            height: 17mm;
            background-color: #eae363;
            margin-left: 65mm;
        }
    .qr, img{
        display: flex;
        justify-content: center;
        text-align: center;
    }

    .hidden{
        display:none;
    }
    .separateur{
        padding: 8mm;
        margin: 5mm;
    }
    .separateurV{
        margin-top: 8mm;
       /* border: 1px solid #eae363;*/
    }
    .milieu{
        padding: 4.8mm;
    }
    .prerempli{
        padding-left: 25mm;
        margin-right: 25mm;
    }

    .tdQR{
        width: 22%;
        padding-right: 4mm;
    }
    .sanspd{
        padding: 0;
        top:0;
        margin: 0;
      /*  background-color: #fa02c4;*/
    }
    .tsamll{
        font-size: 12px;
        font-weight: bold;
    }
    .dateOctroi{
        font-size: 9px;
        font-weight: bold;
    }

    .signature{
        padding-right: 3mm;
        margin: 0;
        width: 2mm;
        height: 1mm;
        /*border: 1px solid #000;*/
    }
    .tcenter{
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        /*background-color: red;*/
    }
   

</style>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <div class="card">
        <table >
            <tr>
                <th colspan="4" class="separateur"></th>
            </tr>
            <tr>
                <td colspan="4" class="tsamll tcenter">{{$vehicule->DGRK}}</td>
            </tr>
            <tr>
                <td colspan="2" ></td>
                <td colspan="2" class="tsamll">
                    {{ $vehicule->proprietaire?->nom_complet ??  $vehicule->proprietaire?->raison_sociale }}<br>
                    {{ $vehicule->proprietaire?->adresse_physique ?? 'Non renseigné' }}<br>
                    {{$vehicule->proprietaire->impot  ?? '-'}} <br>
                </td>
                
            </tr>
                <tr>
                    <td colspan="2" class="prerempli"></td>
                    
                    <td class="tsamll">
                         {{ $vehicule->an_circulation }}<br>
                        {{ $vehicule->usage }}<br>
                        {{ $vehicule->immatriculation }}
                    </td>  
                    <td class="qr tdQR">
                        <img
                            src="{{ $qrCode }}"
                            alt="QR Code du véhicule"
                            width="60"
                            height="60"
                        >
                        <p class="dateOctroi">{{$vehicule->date}}</p> 
                    </td>  
            </tr>
        </table>
        <div class="milieu"></div>
       <table >
           
            <tr>
                <td colspan="2" ></td>
                <td colspan="2" class="tsamll">
                    {{ $vehicule->marque }}<br>
                    {{ $vehicule->cartinspection}}<br>
                    {{$vehicule->chassis ?? '-'}} <br>
                </td>
                
            </tr>
                <tr>
                    <td colspan="2" class="prerempli"></td>
                    <td class="sanspd tsamll">
                        {{ $vehicule->moteur }}<br>
                        {{ $vehicule->an_fabrication }}<br>
                        {{ $vehicule->couleur }} <br>
                        {{ $vehicule->puissance }}
                    </td>  
                     
            </tr>
             <tr>
                <th colspan="3" class="separateurV"></th>
                <td class="signature">
                    <img src="{{public_path('sign/signature.jpg')}}" alt="" width="90" height="30">
                </td> 
            </tr>
        </table>
    </div>
</body>
</html>



    @php
        //die();
    @endphp