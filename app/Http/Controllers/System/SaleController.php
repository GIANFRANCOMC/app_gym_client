<?php

namespace App\Http\Controllers\System;

use App\Helpers\System\Utilities;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, DB};
use stdClass;

use App\Http\Requests\System\Sales\{CancelSaleRequest, StoreSaleRequest, UpdateSaleRequest};
use App\Models\System\{Branch, Currency, Customer, Item, SaleBody, SaleHeader, Subscription};

class SaleController extends Controller {

    public function initParams(Request $request) {

        $initParams = new stdClass();

        $config = new stdClass();

        $page = $request->page ?? "";

        if(in_array($page, ["list"])){

            $config->branches = new stdClass();
            $config->branches->records = Branch::getAll();

            $config->customers = new stdClass();
            $config->customers->records = Customer::getAll();

            $config->salesHeader = new stdClass();
            $config->salesHeader->statusses = SaleHeader::getStatusses();

        }else if(in_array($page, ["main"])){

            $config->branches = new stdClass();
            $config->branches->records = Branch::getAll("sale");

            $config->currencies = new stdClass();
            $config->currencies->records = Currency::get();

            $config->customers = new stdClass();
            $config->customers->records = Customer::getAll("sale");

            $config->items = new stdClass();
            $config->items->records = Item::getAll("sale");

            $config->salesHeader = new stdClass();
            $config->salesHeader->statusses = SaleHeader::getStatusses();

        }

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
                           ->when(Utilities::isDefined($request->status), function($query) use($request) {

                                $query->where("status", $request->status);

                           })
                           ->orderBy("id", "DESC")
                           ->with(["serie.documentType", "holder", "currency"])
                           ->paginate($request->per_page ?? Utilities::$per_page_default);

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

                    if(in_array($detail["type"], ["subscription"])) {

                        $subscription = new Subscription();
                        $subscription->sale_body_id = $saleBody->id;
                        $subscription->customer_id  = $saleHeader->holder_id;
                        $subscription->start_date   = $detail["extras"]["start_date"];
                        $subscription->end_date     = $detail["extras"]["start_date"];
                        $subscription->observation  = $detail["extras"]["observation"] ?? "";
                        $subscription->type         = "sale";
                        $subscription->status       = "active";
                        $subscription->created_at   = now();
                        $subscription->created_by   = $userAuth->id ?? null;
                        $subscription->save();

                    }

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

    public function cancel(CancelSaleRequest $request, $id) {

        $userAuth = Auth::user();

        // Cambiar condicional - y agregar company_id si le pertenece
        $saleHeader = SaleHeader::findOrFail($id);

        if(Utilities::isDefined($saleHeader) && in_array($saleHeader->status, ["active"])) {

            $saleHeader->status     = "cancelled";
            $saleHeader->updated_at = now();
            $saleHeader->updated_by = $userAuth->id ?? null;
            $saleHeader->save();

        }

        $bool = $saleHeader->wasChanged();
        $msg  = $bool ? "Venta anulada correctamente." : "No se ha podido anular la venta.";

        return response()->json(["bool" => $bool, "msg" => $msg, "sale" => $saleHeader], 200);

    }

    public function destroy(SaleHeader $record) {

        //

    }

}
