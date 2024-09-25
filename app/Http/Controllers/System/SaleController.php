<?php

namespace App\Http\Controllers\System;

use App\Helpers\System\Utilities;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, DB};
use stdClass;

use App\Http\Requests\System\Sales\{StoreSaleRequest, UpdateSaleRequest};
use App\Models\System\{Customer, SaleHeader};

class SaleController extends Controller {

    public function initParams(Request $request) {

        $initParams = new stdClass();

        $config = new stdClass();
        $config->salesHeader = new stdClass();
        $config->salesHeader->statusses = SaleHeader::getStatusses();

        $config->customers = new stdClass();
        $config->customers->records = Customer::get();

        $initParams->config = $config;
        $initParams->bool   = true;

        return $initParams;

    }

    public function list(Request $request) {

        //

    }

    public function index() {

        return view("System/general/sales/list");

    }

    public function create() {

        return view("System/general/sales/main");

    }

    public function store(StoreSaleRequest $request) {

        //

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
