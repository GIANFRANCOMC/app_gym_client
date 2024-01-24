<?php

namespace App\Http\Controllers\Admins;

use App\Helpers\Utilities;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, DB};
use stdClass;

use App\Http\Requests\Admins\Sales\{StoreSaleRequest, UpdateSaleRequest};
use App\Models\{Sale};

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
