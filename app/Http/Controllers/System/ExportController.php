<?php

namespace App\Http\Controllers\System;

use App\Helpers\System\Utilities;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, DB};
use stdClass;

use App\Models\System\{Company, Customer, Item, SaleBody, SaleHeader};
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;

class ExportController extends Controller {

    public function index(Request $request) {

        if(Utilities::isDefined($request->document)) {

            $document  = base64_decode($request->document ?? "");
            $printType = $request->type ?? "a4";

            if(Utilities::isDefined($document)) {

                $saleHeader = SaleHeader::where("id", $document)
                                        ->with(["serie.documentType", "holder", "positions"])
                                        ->first();

                $company = Company::first();

                if(Utilities::isDefined($saleHeader) && Utilities::isDefined($company)) {

                    try {

                        $path = public_path("System/assets/img/avatars/1.png");
                        $logo = "data:image/".pathinfo($path, PATHINFO_EXTENSION).";base64,".base64_encode(file_get_contents($path));

                        $data = [
                            "saleHeader" => $saleHeader,
                            "company"    => $company,
                            "extras"     => $saleHeader,
                            "logo"       => $logo
                        ];

                        if(in_array($printType, ["a4"])) {

                            // return view("System.pdf.sales.a4", $data);
                            $pdf = Pdf::loadView("System.pdf.sales.a4", $data);
                            return $pdf->stream("archivo.pdf", ["Attachment" => false]);
                            // return $pdf->download("Comprobante ".$saleHeader->serie_sequential.".pdf");

                        }else if(in_array($printType, ["mm80"])) {

                            // return view("System.pdf.sales.mm80", $data);
                            $pdf = Pdf::loadView("System.pdf.sales.mm80", $data)->setPaper([0, 0, 80 * 2.83, 160 * 2.83]);
                            return $pdf->stream("archivo.pdf", ["Attachment" => false]);
                            // return $pdf->download("Comprobante ".$saleHeader->serie_sequential.".pdf");

                        }

                    }catch(Exception $e) {

                        return response()->view("errors.404", ["msg" => $e->getMessage()], 404);

                    }

                }

            }

        }

        return response()->view("errors.404", ["msg" => "Información no encontrada"], 404);

    }

}
