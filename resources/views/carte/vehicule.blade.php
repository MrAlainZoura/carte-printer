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
        border: 1px solid black;
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
        padding: 6mm;
        margin: 3mm;
    }
    .separateurV{
        margin-top: 15mm;
       border: 1px solid #eae363;
    }
    .milieu{
        position: absolute;
        padding: 6mm;
    }
    .prerempli{
        padding-left: 25mm;
    }
    .prerempliV{
        padding-left: 20mm;
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
        font-size: 9px;
    }
    .dateOctroi{
        font-size: 7px;
    }

    .signature{
        padding-right: 3mm;
        margin: 0;
        width: 2mm;
        height: 1mm;
        display: flex;
        align-items: center;
        text-align: center;
        /*border: 1px solid #000;*/
    }
    .tcenter{
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
    }
    .pdl{
        padding-left: 5mm;
    }
    .mgt{
        margin-top:10mm ;
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
        <div class="separateur"></div>
        <table>
        <tr>
            <td colspan="4" class="tsamll tcenter">
                {{ $vehicule->DGRK }}
            </td>
        </tr>

    <tr>
        <td></td>
        <td></td>
        <td rowspan="2" class="pdl tsamll">
            {{ $vehicule->proprietaire?->adresse_physique ?? '-' }} <br>
            {{ $vehicule->proprietaire?->impot ?? '-' }} <br>
            {{ $vehicule->an_circulation ?? '-' }} <br>
            {{ $vehicule->usage ?? '-' }} <br>
            {{ $vehicule->immatriculation ?? '-' }}
        </td>
        <td class="mgt"></td>
    </tr>

    <tr>
        <td></td>

        <td></td>

        <td class="qr tdQR">
            <div class="mgt">

                <img src="{{ $qrCode }}"
                     alt="QR Code du véhicule"
                     width="50"
                     height="50">
            </div>
        </td>
    </tr>

    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td><p class="dateOctroi">
                {{ $vehicule->date }}
            </p></td>
    </tr>
</table>


        <div class="milieu"></div>

       <table >
            <tr>
                <td colspan="2" ></td>
                <td colspan="2" class="tsamll">
                    {{ $vehicule->marque }}
                </td>
            </tr>
            <tr>
                <td colspan="2" ></td>
                <td colspan="2" class="tsamll">
                    {{ $vehicule->cartinspection}}
                </td>
            </tr>
            <tr>
                <td colspan="2" ></td>
                <td colspan="2" class="tsamll">
                    {{$vehicule->chassis ?? '-'}}
                </td>
            </tr>
            <tr>
                <td colspan="2" class=""></td>
                <td class="sanspd tsamll" colspan="">
                    {{ $vehicule->moteur }}
                </td> 
                 <td></td>
            </tr>
            <tr>
                <td colspan="2" class=""></td>
                <td class="sanspd tsamll">
                    {{ $vehicule->an_fabrication }}
                </td> 
                <td></td> 
            </tr>
            <tr>
                <td colspan="2" class=""></td>
                <td class="sanspd tsamll">
                    {{ $vehicule->couleur }}
                </td> 
                <td></td> 
            </tr>
            <tr>
                <td colspan="2" class=""></td>
                <td class="sanspd tsamll">
                    {{ $vehicule->puissance }}
                </td>  
                <td></td>
            </tr>
             <tr>
                <td colspan="2" class=""></td>
                <td></td>
                <td class="signature" >
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