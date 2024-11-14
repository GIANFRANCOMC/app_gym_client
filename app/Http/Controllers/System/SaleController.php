<?php

namespace App\Http\Controllers\System;

use App\Helpers\System\Utilities;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, DB};
use stdClass;

use App\Http\Requests\System\Sales\{StoreSaleRequest, UpdateSaleRequest};
use App\Models\System\{Branch, Currency, Customer, Item, SaleBody, SaleHeader};

class SaleController extends Controller {

    public function initParams(Request $request) {

        $initParams = new stdClass();

        $config = new stdClass();
        $config->branches = new stdClass();
        $config->branches->records = Branch::with(["series.documentType"])->get();

        $config->currencies = new stdClass();
        $config->currencies->records = Currency::get();

        $config->customers = new stdClass();
        $config->customers->records = Customer::get();

        $config->items = new stdClass();
        $config->items->records = Item::with(["currency"])->get();

        $config->salesHeader = new stdClass();
        $config->salesHeader->statusses = SaleHeader::getStatusses();

        $initParams->config = $config;
        $initParams->bool   = true;

        return $initParams;

    }

    public function list(Request $request) {

        $list = SaleHeader::when(Utilities::isDefined($request->serie_id), function($query) use($request) {

                                $query->where("serie_id", $request->serie_id);

                           })
                           ->when(Utilities::isDefined($request->sequential), function($query) use($request) {

                                $query->where("sequential", $request->sequential);

                           })
                           ->when(Utilities::isDefined($request->holder_id), function($query) use($request) {

                                $query->where("holder_id", $request->holder_id);

                           })
                           ->when(Utilities::isDefined($request->issue_date), function($query) use($request) {

                                $query->where("issue_date", $request->issue_date);

                           })
                           ->orderBy("id", "DESC")
                           ->with(["serie.documentType", "holder", "currency"])
                           ->paginate(10);

        return $list;

    }

    public function index() {

        return view("System/general/sales/list");

    }

    public function create() {

        return view("System/general/sales/main");

    }

    public function store(StoreSaleRequest $request) {

        $userAuth = Auth::user();

        $saleHeader = null;

        DB::transaction(function() use($request, $userAuth, &$saleHeader) {

            $newSequential = SaleHeader::getNewSequential($request->serie_id);

            if($newSequential > 0) {

                $total = 0;

                $saleHeader = new SaleHeader();
                $saleHeader->serie_id    = $request->serie_id;
                $saleHeader->sequential  = $newSequential;
                $saleHeader->holder_id   = $request->holder_id;
                $saleHeader->seller_id   = $userAuth->id;
                $saleHeader->currency_id = $request->currency_id;
                $saleHeader->issue_date  = $request->issue_date;
                $saleHeader->total       = 0;
                $saleHeader->observation = $request->observation ?? "";
                $saleHeader->status      = "inactive";
                $saleHeader->created_at  = now();
                $saleHeader->created_by  = $userAuth->id ?? null;
                $saleHeader->save();

                foreach($request["details"] as $detail) {

                    $saleBody = new SaleBody();
                    $saleBody->sale_header_id = $saleHeader->id;
                    $saleBody->item_id        = $detail["item_id"];
                    $saleBody->currency_id    = $detail["currency_id"];
                    $saleBody->name           = $detail["name"];
                    $saleBody->quantity       = $detail["quantity"];
                    $saleBody->price          = $detail["price"];
                    $saleBody->total          = Utilities::round((floatval($saleBody->quantity) * floatval($saleBody->price)));
                    $saleBody->observation    = $detail["observation"] ?? "";
                    $saleBody->status         = "active";
                    $saleBody->created_at     = now();
                    $saleBody->created_by     = $userAuth->id ?? null;
                    $saleBody->save();

                    $total += floatval($saleBody->total);

                }

                $saleHeader->total  = $total;
                $saleHeader->status = "active";
                $saleHeader->save();

                // With
                // $saleHeader->serie;
                // $saleHeader->serie_sequential;

            }

        });

        $bool = Utilities::isDefined($saleHeader);
        $msg  = $bool ? "Venta creada correctamente." : "No se ha podido crear la venta.";

        return response()->json(["bool" => $bool, "msg" => $msg, "sale" => $saleHeader], 200);

    }

    public function show(SaleHeader $record) {

        //

    }

    public function edit(SaleHeader $record) {

        //

    }

    public function update(UpdateSaleRequest $request, $id) {

        //

    }

    public function destroy(SaleHeader $record) {

        //

    }

}
