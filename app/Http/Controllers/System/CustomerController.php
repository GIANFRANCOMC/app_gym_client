<?php

namespace App\Http\Controllers\System;

use App\Helpers\System\Utilities;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, DB};
use stdClass;

use App\Http\Requests\System\Customers\{StoreCustomerRequest, UpdateCustomerRequest};
use App\Models\System\{Customer, IdentityDocumentType};

class CustomerController extends Controller {

    public function initParams(Request $request) {

        $initParams = new stdClass();

        $config = new stdClass();
        $config->customers = new stdClass();
        $config->customers->statusses = Customer::getStatusses();

        $config->identityDocumentTypes = new stdClass();
        $config->identityDocumentTypes->records = IdentityDocumentType::get();

        $initParams->config = $config;
        $initParams->bool   = true;

        return $initParams;

    }

    public function list(Request $request) {

        $list = Customer::when(Utilities::isDefined($request->general), function($query) use($request) {

                            $filter = "%".trim($request->general)."%";

                            $query->where("name", "like", $filter);

                        })
                        ->orderBy("name", "ASC")
                        ->with(["identityDocumentType"])
                        ->paginate(10);

        return $list;

    }

    public function index() {

        return view("System/general/customers/main");

    }

    public function create() {

        //

    }

    public function store(StoreCustomerRequest $request) {

        $userAuth = Auth::user();

        $customer = new Customer();

        DB::transaction(function() use($request, $userAuth, $customer) {

            $customer->identity_document_type_id = $request->identity_document_type_id;
            $customer->document_number           = $request->document_number;
            $customer->name                      = $request->name;
            $customer->status                    = $request->status;
            $customer->created_at                = now();
            $customer->created_by                = $userAuth->id ?? null;
            $customer->save();

        });

        return response()->json(["bool" => true, "msg" => "Cliente creado correctamente.", "customer" => $customer], 200);

    }

    public function show(Customer $record) {

        //

    }

    public function edit(Customer $record) {

        //

    }

    public function update(UpdateCustomerRequest $request, $id) {

        $userAuth = Auth::user();

        $customer = Customer::where("id", $id)
                    ->firstOrFail();

        DB::transaction(function() use($request, $userAuth, $customer) {

            $customer->identity_document_type_id = $request->identity_document_type_id;
            $customer->document_number           = $request->document_number;
            $customer->name                      = $request->name;
            $customer->status                    = $request->status;
            $customer->updated_at                = now();
            $customer->updated_by                = $userAuth->id ?? null;
            $customer->save();

        });

        return response()->json(["bool" => true, "msg" => "Cliente editado correctamente.", "customer" => $customer], 200);

    }

    public function destroy(Customer $record) {

        //

    }

}
