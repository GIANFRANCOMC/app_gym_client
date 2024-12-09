<?php

namespace App\Http\Controllers\System;

use App\Exports\{BranchExport, CustomerExport, ItemExport, SaleExport, UserExport};
use App\Helpers\System\Utilities;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, DB};
use stdClass;

use App\Models\System\{Branch, Company, Customer, Item, SaleBody, SaleHeader, User};
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller {

    public function initParams(Request $request) {

        $initParams = new stdClass();

        $config = new stdClass();

        $page = $request->page ?? "";

        if(in_array($page, ["main"])) {

            //

        }

        $initParams->config = $config;
        $initParams->bool   = true;

        return $initParams;

    }

    public function index() {

        return view("System/general/reports/main");

    }

    public function indexz(Request $request) {

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

                        $logoPath = public_path("System/assets/img/avatars/1.png");
                        $logoImg  = "data:image/".pathinfo($logoPath, PATHINFO_EXTENSION).";base64,".base64_encode(file_get_contents($logoPath));

                        $cancelledPath = public_path("System/assets/img/utils/sales/cancelled.png");
                        $cancelledImg  = "data:image/".pathinfo($cancelledPath, PATHINFO_EXTENSION).";base64,".base64_encode(file_get_contents($cancelledPath));

                        $data = [
                            "saleHeader"   => $saleHeader,
                            "company"      => $company,
                            "extras"       => $saleHeader,
                            "logoImg"      => $logoImg,
                            "cancelledImg" => $cancelledImg
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

                        return response()->view("errors.500", ["msg" => $e->getMessage()], 500);

                    }

                }

            }

        }

        return response()->view("errors.404", ["msg" => "Información no encontrada"], 404);

    }

    public function customers(Request $request) {

        $customers = Customer::when(Utilities::isDefined($request->document_number), function($query) use($request) {

                                $filter = "%".trim($request->document_number)."%";

                                $query->where("document_number", "like", $filter);

                             })
                             ->when(Utilities::isDefined($request->name), function($query) use($request) {

                                $filter = "%".trim($request->name)."%";

                                $query->where("name", "like", $filter);

                             })
                             ->with(["identityDocumentType"])
                             ->get();

        $data = collect([]);

        foreach($customers as $customer) {

            $record = new stdClass;
            $record->documentType    = $customer->identityDocumentType->name;
            $record->document_number = $customer->document_number;
            $record->name            = $customer->name;
            $record->email           = $customer->email;
            $record->status          = $customer->formatted_status;

            $data->push($record);

        }

        return Excel::download(new CustomerExport($data), "Clientes.xlsx");

    }

    public function users(Request $request) {

        $users = User::when(Utilities::isDefined($request->document_number), function($query) use($request) {

                        $filter = "%".trim($request->document_number)."%";

                        $query->where("document_number", "like", $filter);

                     })
                     ->when(Utilities::isDefined($request->name), function($query) use($request) {

                        $filter = "%".trim($request->name)."%";

                        $query->where("name", "like", $filter);

                     })
                     ->with(["identityDocumentType"])
                     ->get();

        $data = collect([]);

        foreach($users as $user) {

            $record = new stdClass;
            $record->documentType    = $user->identityDocumentType->name;
            $record->document_number = $user->document_number;
            $record->name            = $user->name;
            $record->email           = $user->email;
            $record->status          = $user->formatted_status;

            $data->push($record);

        }

        return Excel::download(new UserExport($data), "Colaboradores.xlsx");

    }

    public function items(Request $request) {

        $items = Item::when(Utilities::isDefined($request->name), function($query) use($request) {

                        $filter = "%".trim($request->name)."%";

                        $query->where("name", "like", $filter);

                     })
                     ->with(["currency"])
                     ->get();

        $data = collect([]);

        foreach($items as $item) {

            $record = new stdClass;
            $record->name        = $item->name;
            $record->description = $item->description;
            $record->price       = $item->price;
            $record->currency    = $item->currency->plural_name;
            $record->status      = $item->formatted_status;

            $data->push($record);

        }

        return Excel::download(new ItemExport($data), "Productos - Servicios.xlsx");

    }



    public function branches(Request $request) {

        $branches = Branch::when(Utilities::isDefined($request->name), function($query) use($request) {

                            $filter = "%".trim($request->name)."%";

                            $query->where("name", "like", $filter);

                          })
                          ->get();

        $data = collect([]);

        foreach($branches as $branch) {

            $record = new stdClass;
            $record->name   = $branch->name;
            $record->status = $branch->formatted_status;

            $data->push($record);

        }

        return Excel::download(new BranchExport($data), "Sucursales.xlsx");

    }

    public function sales(Request $request) {

        $salesHeader = SaleHeader::when(Utilities::isDefined($request->type), function($query) use($request) {

                                    if(in_array($request->type, ["by_month"])) {

                                        if(Utilities::isDefined($request->start_month)) {

                                            list($year, $month) = explode("-", $request->start_month);

                                            $query->whereYear("issue_date", $year)
                                                  ->whereMonth("issue_date", $month);

                                        }

                                    }else if(in_array($request->type, ["range_months"])) {

                                        if(Utilities::isDefined($request->start_date) && Utilities::isDefined($request->end_date)) {

                                            dd("pendiente");
                                            $query->where("issue_date", ">=", $request->start_date."-01")
                                                  ->where("issue_date", "<=", $request->end_date."-31");

                                        }

                                    }else if(in_array($request->type, ["by_date"])) {

                                        if(Utilities::isDefined($request->start_date)) {

                                            $query->where("issue_date", $request->start_date);

                                        }

                                    }else if(in_array($request->type, ["range_dates"])) {

                                        if(Utilities::isDefined($request->start_date) && Utilities::isDefined($request->end_date)) {

                                            $query->where("issue_date", ">=", $request->start_date)
                                                  ->where("issue_date", "<=", $request->end_date);

                                        }

                                    }

                                 })
                                 ->with(["holder", "currency"])
                                 ->get();

        $data = collect([]);

        foreach($salesHeader as $saleHeader) {

            $record = new stdClass;
            $record->serie_sequential     = $saleHeader->serie_sequential;
            $record->holder               = $saleHeader->holder->name;
            $record->formatted_issue_date = $saleHeader->formatted_issue_date;
            $record->total                = $saleHeader->total;
            $record->currency             = $saleHeader->currency->plural_name;
            $record->status               = $saleHeader->formatted_status;

            $data->push($record);

        }

        return Excel::download(new SaleExport($data), "Ventas.xlsx");

    }

}
