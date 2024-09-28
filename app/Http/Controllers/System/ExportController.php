<?php

namespace App\Http\Controllers\System;

use App\Helpers\System\Utilities;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, DB};
use stdClass;

use App\Http\Requests\System\Sales\{StoreSaleRequest, UpdateSaleRequest};
use App\Models\System\{Company, Customer, Item, SaleBody, SaleHeader};
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;

class ExportController extends Controller {

    public function index(Request $request) {

        $saleHeader = SaleHeader::with(["customer", "positions"])->first();
        $company    = Company::first();

        try {

            $data = [
                "saleHeader" => $saleHeader,
                "company" => $company,
                "extras"  => $saleHeader
            ];

            //return view("System.pdf.sales.a4", $data);
            //$pdf = Pdf::loadView('System.pdf.sales.a4', $data); return $pdf->download("Ficha de matrícula $saleHeader->id.pdf");

            //return view("System.pdf.sales.80mm", $data);
            $pdf = Pdf::loadView('System.pdf.sales.80mm', $data)->setPaper([0, 0, 80 * 2.83, 160 * 2.83]);

            return $pdf->download("Comprobante $saleHeader->id.pdf");

        }catch(Exception $e) {

            return "Error: ".$e->getMessage();

        }

    }

}
