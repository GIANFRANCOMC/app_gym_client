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

        $id = $request->id;

        $saleHeader = SaleHeader::where("id", $id)
                                ->with(["serie", "holder", "positions"])
                                ->first();

        $company = Company::first();

        try {

            $data = [
                "saleHeader" => $saleHeader,
                "company" => $company,
                "extras"  => $saleHeader
            ];

            $printType = $request->type;

            if(in_array($printType, ["a4"])) {

                // return view("System.pdf.sales.a4", $data);
                $pdf = Pdf::loadView('System.pdf.sales.a4', $data);
                return $pdf->download("Comprobante ".SaleHeader::getSerieSequential($saleHeader).".pdf");

            }else if(in_array($printType, ["mm80"])) {

                // return view("System.pdf.sales.mm80", $data);
                $pdf = Pdf::loadView('System.pdf.sales.mm80', $data)->setPaper([0, 0, 80 * 2.83, 160 * 2.83]);
                return $pdf->download("Comprobante ".SaleHeader::getSerieSequential($saleHeader).".pdf");

            }

        }catch(Exception $e) {

            return "Error: ".$e->getMessage();

        }

    }

}
