<?php

namespace App\Http\Controllers\Admins;

use App\Helpers\Utilities;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, DB};
use stdClass;

use App\Http\Requests\Admins\Sales\{StoreSaleRequest, UpdateSaleRequest};
use App\Models\{Branch, Customer, Membership, ProductService, Sale};

class SaleController extends Controller {

    /**
     * Display init params.
     *
     * @param  \App\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function initParams(Request $request) {

        $userAuth = Auth::user();

        $initParams = new stdClass();

        $branches = Branch::where("company_id", $userAuth->company_id)
                          ->where("status", "active")
                          ->orderBy("name", "ASC")
                          ->orderBy("location", "ASC")
                          ->get();

        $customers = Customer::where("company_id", $userAuth->company_id)
                             ->where("status", "active")
                             ->orderBy("last_name", "ASC")
                             ->orderBy("first_name", "ASC")
                             ->get();

        $memberships = Membership::where("company_id", $userAuth->company_id)
                                 ->where("status", "active")
                                 ->orderBy("name", "ASC")
                                 ->orderBy("description", "ASC")
                                 ->get();

        $productServices = ProductService::where("company_id", $userAuth->company_id)
                                         ->where("status", "active")
                                         ->orderBy("name", "ASC")
                                         ->orderBy("description", "ASC")
                                         ->get();

        $detailTypes = [];
        array_push($detailTypes, ["code" => "memberships", "label" => "Membresias"]);
        array_push($detailTypes, ["code" => "productServices", "label" => "Producto - Servicios"]);

        $initParams->branches        = $branches;
        $initParams->customers       = $customers;
        $initParams->memberships     = $memberships;
        $initParams->productServices = $productServices;
        $initParams->detailTypes     = $detailTypes;

        return $initParams;

    }

    /**
     * Display a listing of the resource.
     *
     * @param  \App\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function list(Request $request) {

        //

    }

    /**
     * Display a listing of the resource.
     */
    public function index() {

        return view("admins/sales/main");

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {

        return view("admins/sales/create");

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSaleRequest $request) {

        //

    }

    /**
     * Display the specified resource.
     */
    public function show(Sale $sale) {

        //

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Sale $sale) {

        //

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSaleRequest $request, Sale $sale) {

        //

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Sale $sale) {

        //

    }

}
