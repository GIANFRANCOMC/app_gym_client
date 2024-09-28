<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Venta</title>

    <style>
        .c-white{
            color: white !important;
        }

        .npm{
            padding: 0px !important;
            margin: 0px !important;
        }

        .mp-table{
            padding: 10px;
            margin: 10px;
            border-width: 1px;
            border-style: solid;
            border-color: black;
        }

        .mp-table-no-boder{
            padding: 10px;
            margin: 10px;
        }

        .center{
            text-align: center !important;
        }

        .left{
            text-align: left !important;
        }

        .right{
            text-align: right !important;
        }
    </style>

    <!-- Margin -->
    <style>
        .mt-10{
            margin-top: 10px !important;
        }

        .mt-20{
            margin-top: 20px !important;
        }

        .mt-30{
            margin-top: 30px !important;
        }

        .m-10{
            margin: 10px !important;
        }
    </style>

    <!-- Padding -->
    <style>
        .p-3{
            padding: 3px !important;
        }

        .p-5{
            padding: 5px !important;
        }

        .p-10{
            padding: 10px !important;
        }
    </style>

    <!-- Width -->
    <style>
        .w-3-75{
            width: 3.75% !important;
        }

        .w-7-5{
            width: 7.5% !important;
        }

        .w-14{
            width: 14% !important;
        }

        .w-15{
            width: 15% !important;
        }

        .w-16{
            width: 16% !important;
        }

        .w-16-6{
            width: 16.6% !important;
        }

        .w-16-8{
            width: 16.8% !important;
        }

        .w-19{
            width: 19% !important;
        }

        .w-20{
            width: 20% !important;
        }

        .w-21{
            width: 21% !important;
        }

        .w-25{
            width: 25% !important;
        }

        .w-30{
            width: 30% !important;
        }

        .w-35{
            width: 35% !important;
        }

        .w-36-4{
            width: 36.4% !important;
        }

        .w-36-6{
            width: 36.6% !important;
        }

        .w-40{
            width: 40% !important;
        }

        .w-45{
            width: 45% !important;
        }

        .w-50{
            width: 50% !important;
        }

        .w-50-4{
            width: 50.4% !important;
        }

        .w-54{
            width: 54% !important;
        }

        .w-60{
            width: 60% !important;
        }

        .w-80{
            width: 80% !important;
        }

        .w-85{
            width: 85% !important;
        }

        .w-100{
            width: 100% !important;
        }
    </style>

    <!-- Height -->
    <style>
        .h-80px{
            height: 75px !important;
        }
    </style>

    <!-- Table -->
    <style>
        table.table-border {
            border-width: 1px;
            border-spacing: 5px;
            border-style: solid;
            border-color: black;
            border-collapse: collapse;
            background-color: white;
            width: 100% !important;
        }

        table.table-border th {
            border-width: 1px;
            padding: 2px;
            border-style: solid;
            border-color: black;
            background-color: white;
        }

        table.table-border td {
            border-width: 1px;
            padding: 2px;
            border-style: solid;
            border-color: black;
            background-color: white;
            -moz-border-radius: ;
        }

        table.table-no-boder {
            border-spacing: 5px;
            border-collapse: collapse;
            background-color: white;
            width: 100% !important;
        }

        table.table-no-boder th {
            padding: 2px;
            background-color: white;
            -moz-border-radius: ;
        }

        table.table-no-boder td {
            padding: 2px;
            background-color: white;
            -moz-border-radius: ;
        }

        .without-boder-top{
            border-top: hidden !important;
        }

        .without-boder-bottom{
            border-bottom: hidden !important;
        }
    </style>

    <!-- Default -->
    <style>
        .div-w-100{
            margin: 0 auto !important;
            display: block !important;
            width: 100% !important;
        }

        .margin-padding-foto-border{
            padding: 10px;
            margin: 25px;
            border-width: 1px;
            border-style: solid;
            border-color: black;
        }

        .border-black{
            border-width: 1px;
            border-style: solid;
            border-color: black;
        }

        .page-break{
            page-break-after: always;
        }
    </style>

    <!-- Font size -->
    <style>
        .fs-0-50{
            font-size: 0.50em;
        }

        .fs-0-55{
            font-size: 0.55em;
        }

        .fs-0-60{
            font-size: 0.60em;
        }

        .fs-0-65{
            font-size: 0.65em;
        }

        .fs-0-70{
            font-size: 0.70em;
        }

        .fs-0-75{
            font-size: 0.75em;
        }

        .fs-1{
            font-size: 1em;
        }

        .fs-1-75{
            font-size: 1.75em;
        }

        .fs-2{
            font-size: 2em;
        }
    </style>

    <!-- Font type -->
    <style>
        .bold{
            font-weight: bold;
        }
    </style>

    <!-- Font family -->
    <style>
        .arial{
            font-family: Arial, Helvetica, sans-serif;
        }

        @font-face {
            font-family: 'arialBlack';
            src: url({{ storage_path('fonts\arialBlack.ttf') }}) format("truetype");
        }

        .arialBlack400 {
            font-family: 'arialBlack';
            font-weight: 400px;
            font-style: normal;
        }
    </style>

    <style>
         @page {
            margin: 0;
        }
        body {
            margin: 0;
        }
    </style>
</head>
<body>
    <div class="div-w-100">
        <table class="table-no-boder fs-0-65">
            <tr>
                <td class="w-100 center">
                    <img src="{{ asset('System/assets/img/favicon/favicon.ico') }}" alt="Logo" height="100px">
                </td>
            </tr>
            <tr>
                <td class="w-100 center">
                    {{ $company->commercial_name }}
                </td>
            </tr>
            <tr>
                <td class="w-100 center">
                    {{ $company->formatted_document_type }} {{ $company->document_number }}
                </td>
            </tr>
            <tr>
                <td class="w-100 center">
                    Dirección: Av. Alto Cayalti #313
                </td>
            </tr>
            <tr>
                <td class="w-100 center">
                    Central telefónica: 879098778
                </td>
            </tr>
            <tr>
                <td class="w-100 center">
                    Correo electrónico: gian@hotmail.com
                </td>
            </tr>
        </table>
    </div>
    <div class="div-w-100">
        <table class="table-no-boder fs-0-70">
            <tr>
                <td class="bold center">
                    BOLETA DE VENTA
                </td>
            </tr>
            <tr>
                <td class="bold center">
                    {{ $saleHeader->sequential }}
                </td>
            </tr>
        </table>
    </div>
    <div class="div-w-100">
        <div class="mt-10">
            <table class="table-no-border arial fs-0-60">
                <tr class="left">
                    <td class="bold">FECHA DE EMISIÓN:</td>
                    <td class="">{{ $saleHeader->formatted_sale_date }}</td>
                </tr>
                <tr class="left">
                    <td class="bold">CLIENTE:</td>
                    <td class="">{{ $saleHeader->customer->name }}</td>
                </tr>
            </table>
        </div>
    </div>
    <div class="div-w-100">
        <div class="p-5">
            <table class="table-border arial fs-0-55">
                <thead>
                    <tr>
                        <th class="w-40">DESCRIPCIÓN</th>
                        <th class="w-10">CANT.</th>
                        <th class="w-10">UNIDAD</th>
                        <th class="w-20">P. UNIT</th>
                        <th class="w-20">TOTAL</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($saleHeader->positions as $position)
                        <tr>
                            <td class="w-40 left">{{ $position->name }}</td>
                            <td class="w-10 center">{{ $position->quantity }}</td>
                            <td class="w-10 center">UND</td>
                            <td class="w-20 center">S/ {{ $position->price }}</td>
                            <td class="w-20 center">S/ {{ floatval($position->quantity) * floatval($position->price) }}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="4" class="right bold">TOTAL A PAGAR</td>
                        <td colspan="1" class="bold">S/ {{ $saleHeader->total }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="div-w-100">
        <table class="table-no-boder arial fs-0-60">
            <tr>
                <td>
                    <span>Son:</span>
                    <span class="bold">{{ $saleHeader->legible_total }}</span>
                </td>
            </tr>
        </table>
    </div>
</body>

</html>
