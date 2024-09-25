<?php

namespace App\Http\Controllers\System;

use App\Helpers\System\Utilities;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, DB};
use stdClass;

use App\Http\Requests\System\Sales\{StoreSaleRequest, UpdateSaleRequest};
use App\Models\System\{Customer, Item, SaleBody, SaleHeader};

class SaleController extends Controller {

    public function initParams(Request $request) {

        $initParams = new stdClass();

        $config = new stdClass();
        $config->salesHeader = new stdClass();
        $config->salesHeader->statusses = SaleHeader::getStatusses();

        $config->customers = new stdClass();
        $config->customers->records = Customer::get();

        $config->items = new stdClass();
        $config->items->records = Item::get();

        $initParams->config = $config;
        $initParams->bool   = true;

        return $initParams;

    }

    public function list(Request $request) {

        $list = SaleHeader::when(Utilities::isDefined($request->general), function($query) use($request) {

                                $filter = "%".trim($request->general)."%";

                                $query->where("sequential", "like", $filter);

                           })
                           ->orderBy("id", "DESC")
                           ->with(["customer"])
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

        $saleHeader = new SaleHeader();

        DB::transaction(function() use($request, $userAuth, $saleHeader) {

            $saleHeader->sequential  = random_int(1, 200);
            $saleHeader->customer_id = $request->customer_id;
            $saleHeader->sale_date   = $request->sale_date;
            $saleHeader->status      = "active";
            $saleHeader->created_at  = now();
            $saleHeader->created_by  = $userAuth->id ?? null;
            $saleHeader->save();

            foreach($request["details"] as $detail) {

                $saleBody = new SaleBody();
                $saleBody->sale_header_id = $saleHeader->id;
                $saleBody->item_id        = $detail["item"]["id"];
                $saleBody->name           = $detail["name"];
                $saleBody->price          = $detail["price"];
                $saleBody->quantity       = $detail["quantity"];
                $saleBody->status         = "active";
                $saleBody->created_at     = now();
                $saleBody->created_by     = $userAuth->id ?? null;
                $saleBody->save();

            }

        });

        return response()->json(["bool" => true, "msg" => "Venta creada correctamente.", "sale" => $saleHeader], 200);

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
