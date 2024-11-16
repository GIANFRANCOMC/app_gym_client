<html>
<head>
    <style>
        body {
            font-family: sans-serif;
        }
        table {
            border-spacing: 0;
            border-collapse: collapse;
        }
        .font-md {
            font-size: 12px;
        }
        .font-lg {
            font-size: 14px !important;
        }
        .font-xlg {
            font-size: 16px !important;
        }
        .font-bold {
            font-weight: bold;
        }

        .company_logo {
            max-height: 100px;
        }
        .company_logo_box {
            height: 100px;
        }
        .company_logo_ticket {
            max-width: 200px;
            max-height: 80px
        }
        .contain {object-fit: cover;}

        .text-right {
            text-align: right !important;
        }
        .text-center {
            text-align: center !important;
        }
        .text-left {
            text-align: left !important;
        }
        .align-top {
            vertical-align: top !important;
        }

        .full-width {
            width: 100%;
        }

        .m-10 {
            margin: 10px;
        }
        .mt-10 {
            margin-top: 10px;
        }
        .mb-10 {
            margin-bottom: 10px;
        }
        .m-20 {
            margin: 20px;
        }
        .mt-20 {
            margin-top: 20px;
        }
        .mb-20 {
            margin-bottom: 20px;
        }

        .p-20 {
            padding: 20px;
        }
        .pt-20 {
            padding-top: 20px;
        }
        .pb-20 {
            padding-bottom: 20px;
        }
        .p-10 {
            padding: 10px;
        }
        .pt-10 {
            padding-top: 10px;
        }
        .pb-10 {
            padding-bottom: 10px;
        }

        .border-box {
            border: thin solid #333;
        }
        .border-top {
            border-top: thin solid #333;
        }
        .border-bottom {
            border-bottom: thin solid #333;
        }
        .border-top-bottom {
            border-top: thin solid #333;
            border-bottom: thin solid #333;
        }

        .bg-grey {
            background-color: #F8F8F8;
        }

        .logo {

        }

        /* Headings */
        h1, h2, h3, h4, h5, h6 {
            font-weight: 200;
            letter-spacing: -1px;
        }

        h1 {
            font-size: 32px;
            line-height: 44px;
            font-weight: 500;
        }

        h2 {
            font-size: 24px;
            font-weight: 500;
            line-height: 42px;
        }

        h3 {
            font-size: 18px;
            font-weight: 400;
            letter-spacing: normal;
            line-height: 24px;
        }

        h4 {
            font-size: 16px;
            font-weight: 400;
            letter-spacing: normal;
            line-height: 27px;
        }

        h5 {
            font-size: 13px;
            font-weight: 300;
            letter-spacing: normal;
            line-height: 18px;
        }

        h6 {
            font-size: 10px;
            font-weight: 300;
            letter-spacing: normal;
            line-height: 18px;
        }

        table, tr, td, th {
            font-size: 12px !important;
        }

        p {
            font-size: 12px !important;
        }

        .desc {
        font-size: 10px !important;
        }

        .desc-9 {
        font-size: 9px !important;
        }


        .m-0 {
        margin: 0 !important;
        }

        .mt-0,
        .my-0 {
        margin-top: 0 !important;
        }

        .mr-0,
        .mx-0 {
        margin-right: 0 !important;
        }

        .mb-0,
        .my-0 {
        margin-bottom: 0 !important;
        }

        .ml-0,
        .mx-0 {
        margin-left: 0 !important;
        }

        .m-1 {
        margin: 0.25rem !important;
        }

        .mt-1,
        .my-1 {
        margin-top: 0.25rem !important;
        }

        .mr-1,
        .mx-1 {
        margin-right: 0.25rem !important;
        }

        .mb-1,
        .my-1 {
        margin-bottom: 0.25rem !important;
        }

        .ml-1,
        .mx-1 {
        margin-left: 0.25rem !important;
        }

        .m-2 {
        margin: 0.5rem !important;
        }

        .mt-2,
        .my-2 {
        margin-top: 0.5rem !important;
        }

        .mr-2,
        .mx-2 {
        margin-right: 0.5rem !important;
        }

        .mb-2,
        .my-2 {
        margin-bottom: 0.5rem !important;
        }

        .ml-2,
        .mx-2 {
        margin-left: 0.5rem !important;
        }

        .m-3 {
        margin: 1rem !important;
        }

        .mt-3,
        .my-3 {
        margin-top: 1rem !important;
        }

        .mr-3,
        .mx-3 {
        margin-right: 1rem !important;
        }

        .mb-3,
        .my-3 {
        margin-bottom: 1rem !important;
        }

        .ml-3,
        .mx-3 {
        margin-left: 1rem !important;
        }

        .m-4 {
        margin: 1.5rem !important;
        }

        .mt-4,
        .my-4 {
        margin-top: 1.5rem !important;
        }

        .mr-4,
        .mx-4 {
        margin-right: 1.5rem !important;
        }

        .mb-4,
        .my-4 {
        margin-bottom: 1.5rem !important;
        }

        .ml-4,
        .mx-4 {
        margin-left: 1.5rem !important;
        }

        .m-5 {
        margin: 3rem !important;
        }

        .mt-5,
        .my-5 {
        margin-top: 3rem !important;
        }

        .mr-5,
        .mx-5 {
        margin-right: 3rem !important;
        }

        .mb-5,
        .my-5 {
        margin-bottom: 3rem !important;
        }

        .ml-5,
        .mx-5 {
        margin-left: 3rem !important;
        }

        .p-0 {
        padding: 0 !important;
        }

        .pt-0,
        .py-0 {
        padding-top: 0 !important;
        }

        .pr-0,
        .px-0 {
        padding-right: 0 !important;
        }

        .pb-0,
        .py-0 {
        padding-bottom: 0 !important;
        }

        .pl-0,
        .px-0 {
        padding-left: 0 !important;
        }

        .p-1 {
        padding: 0.25rem !important;
        }

        .pt-1,
        .py-1 {
        padding-top: 0.25rem !important;
        }

        .pr-1,
        .px-1 {
        padding-right: 0.25rem !important;
        }

        .pb-1,
        .py-1 {
        padding-bottom: 0.25rem !important;
        }

        .pl-1,
        .px-1 {
        padding-left: 0.25rem !important;
        }

        .p-2 {
        padding: 0.5rem !important;
        }

        .pt-2,
        .py-2 {
        padding-top: 0.5rem !important;
        }

        .pr-2,
        .px-2 {
        padding-right: 0.5rem !important;
        }

        .pb-2,
        .py-2 {
        padding-bottom: 0.5rem !important;
        }

        .pl-2,
        .px-2 {
        padding-left: 0.5rem !important;
        }

        .p-3 {
        padding: 1rem !important;
        }

        .pt-3,
        .py-3 {
        padding-top: 1rem !important;
        }

        .pr-3,
        .px-3 {
        padding-right: 1rem !important;
        }

        .pb-3,
        .py-3 {
        padding-bottom: 1rem !important;
        }

        .pl-3,
        .px-3 {
        padding-left: 1rem !important;
        }

        .p-4 {
        padding: 1.5rem !important;
        }

        .pt-4,
        .py-4 {
        padding-top: 1.5rem !important;
        }

        .pr-4,
        .px-4 {
        padding-right: 1.5rem !important;
        }

        .pb-4,
        .py-4 {
        padding-bottom: 1.5rem !important;
        }

        .pl-4,
        .px-4 {
        padding-left: 1.5rem !important;
        }

        .p-5 {
        padding: 3rem !important;
        }

        .pt-5,
        .py-5 {
        padding-top: 3rem !important;
        }

        .pr-5,
        .px-5 {
        padding-right: 3rem !important;
        }

        .pb-5,
        .py-5 {
        padding-bottom: 3rem !important;
        }

        .pl-5,
        .px-5 {
        padding-left: 3rem !important;
        }

        .m-n1 {
        margin: -0.25rem !important;
        }

        .mt-n1,
        .my-n1 {
        margin-top: -0.25rem !important;
        }

        .mr-n1,
        .mx-n1 {
        margin-right: -0.25rem !important;
        }

        .mb-n1,
        .my-n1 {
        margin-bottom: -0.25rem !important;
        }

        .ml-n1,
        .mx-n1 {
        margin-left: -0.25rem !important;
        }

        .m-n2 {
        margin: -0.5rem !important;
        }

        .mt-n2,
        .my-n2 {
        margin-top: -0.5rem !important;
        }

        .mr-n2,
        .mx-n2 {
        margin-right: -0.5rem !important;
        }

        .mb-n2,
        .my-n2 {
        margin-bottom: -0.5rem !important;
        }

        .ml-n2,
        .mx-n2 {
        margin-left: -0.5rem !important;
        }

        .m-n3 {
        margin: -1rem !important;
        }

        .mt-n3,
        .my-n3 {
        margin-top: -1rem !important;
        }

        .mr-n3,
        .mx-n3 {
        margin-right: -1rem !important;
        }

        .mb-n3,
        .my-n3 {
        margin-bottom: -1rem !important;
        }

        .ml-n3,
        .mx-n3 {
        margin-left: -1rem !important;
        }

        .m-n4 {
        margin: -1.5rem !important;
        }

        .mt-n4,
        .my-n4 {
        margin-top: -1.5rem !important;
        }

        .mr-n4,
        .mx-n4 {
        margin-right: -1.5rem !important;
        }

        .mb-n4,
        .my-n4 {
        margin-bottom: -1.5rem !important;
        }

        .ml-n4,
        .mx-n4 {
        margin-left: -1.5rem !important;
        }

        .m-n5 {
        margin: -3rem !important;
        }

        .mt-n5,
        .my-n5 {
        margin-top: -3rem !important;
        }

        .mr-n5,
        .mx-n5 {
        margin-right: -3rem !important;
        }

        .mb-n5,
        .my-n5 {
        margin-bottom: -3rem !important;
        }

        .ml-n5,
        .mx-n5 {
        margin-left: -3rem !important;
        }

        .m-auto {
        margin: auto !important;
        }

        .mt-auto,
        .my-auto {
        margin-top: auto !important;
        }

        .mr-auto,
        .mx-auto {
        margin-right: auto !important;
        }

        .mb-auto,
        .my-auto {
        margin-bottom: auto !important;
        }

        .ml-auto,
        .mx-auto {
        margin-left: auto !important;
        }
    </style>
</head>
<body>
    <div class="company_logo_box" style="position: absolute; text-align: center; top:20%;">
        <img src="{{ $cancelledImg }}" style="opacity: 0.4;" width="30%">
    </div>
    <table class="full-width">
        <tr>
            <td width="20%">
                <div class="company_logo_box">
                    <img src="{{ $logoImg }}" alt="Logo" class="company_logo" style="max-width: 150px;">
                </div>
            </td>
            <td width="50%" class="pl-3">
                <div class="text-left">
                    <p class="m-0 font-xlg font-bold">{{ $company->commercial_name }}</p>
                    <p class="m-0 font-lg font-bold">{{ $company->identityDocumentType->name }} {{ $company->document_number }}</p>
                    <p class="m-0" style="text-transform: uppercase">Dirección: Av. Alto Cayalti #313</p>
                    <p class="m-0" style="text-transform: uppercase">Central telefónica: 879098778</p>
                    <p class="m-0" style="text-transform: uppercase">Correo electrónico: gian@hotmail.com</p>
                </div>
            </td>
            <td width="30%" class="border-box py-4 px-2">
                <p class="m-0 font-xlg font-bold text-center">{{ $saleHeader->serie->documentType->name }}</p>
                <p class="m-0 font-lg font-bold text-center">{{ $saleHeader->serie_sequential }}</p>
            </td>
        </tr>
    </table>
    <table class="full-width mt-5">
        <tr>
            <td width="120px">FECHA DE EMISIÓN</td>
            <td width="8px">:</td>
            <td>{{$saleHeader->formatted_issue_date}}</td>
        </tr>
        <tr>
            <td style="vertical-align: top;">CLIENTE:</td>
            <td style="vertical-align: top;">:</td>
            <td style="vertical-align: top;">{{ $saleHeader->holder->name }}</td>
        </tr>
    </table>
    <table class="full-width mt-10 mb-10">
        <thead>
            <tr class="bg-grey">
                <th class="border-top-bottom text-center py-2">DESCRIPCIÓN</th>
                <th class="border-top-bottom text-center py-2">CANTIDAD</th>
                <th class="border-top-bottom text-center py-2">UNIDAD</th>
                <th class="border-top-bottom text-right py-2">P. UNITARIO</th>
                <th class="border-top-bottom text-right py-2">TOTAL</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($saleHeader->positions as $position)
                <tr>
                    <td class="text-center align-top">{{ $position->name }}</td>
                    <td class="text-center align-top">{{ $position->quantity }}</td>
                    <td class="text-center align-top">UND</td>
                    <td class="text-right align-top">S/ {{ $position->price }}</td>
                    <td class="text-right align-top">S/ {{ $position->total }}</td>
                </tr>
            @endforeach
            <tr>
                <td colspan="5" class="border-bottom"></td>
            </tr>
            <tr>
                <td colspan="4" class="text-right font-bold">TOTAL A PAGAR:</td>
                <td colspan="1" class="text-right font-bold">S/ {{ number_format(($saleHeader->total), 2) }}</td>
            </tr>
        </tbody>
    </table>
    <table class="full-width">
        <tr>
            <td width="65%" style="text-align: top; vertical-align: top;">
                <span>Son:</span>
                <span class="font-bold">{{ $saleHeader->legible_total }}</span>
            </td>
            <td width="35%" class="text-right">

            </td>
        </tr>
    </table>
</body>

</html>
