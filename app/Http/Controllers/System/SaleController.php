<?php

namespace App\Http\Controllers\System;

use App\Helpers\System\Utilities;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, DB};
use stdClass;

use App\Http\Requests\System\Sales\{StoreSaleRequest, UpdateSaleRequest};
use App\Models\System\{Sale};

class SaleController extends Controller {

    public function initParams(Request $request) {

        $initParams = new stdClass();

        $config = new stdClass();
        // $config->items = new stdClass();
        // $config->items->statusses = Item::getStatusses();

        // $initParams->config = $config;
        $initParams->bool   = true;

        return $initParams;

    }

    public function list(Request $request) {

        //

    }

    public function index() {

        return view("System/general/sales/main");

    }

    public function create() {

        //

    }

    public function store(StoreSaleRequest $request) {

        //

    }

    public function show(Sale $record) {

        //

    }

    public function edit(Sale $record) {

        //

    }

    public function update(UpdateSaleRequest $request, $id) {

        //

    }

    public function destroy(Sale $record) {

        //

    }

}
