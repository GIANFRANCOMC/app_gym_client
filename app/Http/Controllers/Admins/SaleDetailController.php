<?php

namespace App\Http\Controllers\Admins;

use App\Helpers\Utilities;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, DB};
use stdClass;

use App\Http\Requests\Admins\SaleDetails\{StoreSaleDetailRequest, UpdateSaleDetailRequest};
use App\Models\{SaleDetail};

class SaleDetailController extends Controller {

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

        //

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {

        //

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSaleDetailRequest $request) {

        //

    }

    /**
     * Display the specified resource.
     */
    public function show(SaleDetail $saleDetail) {

        //

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SaleDetail $saleDetail) {

        //

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSaleDetailRequest $request, SaleDetail $saleDetail) {

        //

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SaleDetail $saleDetail) {

        //

    }

}
