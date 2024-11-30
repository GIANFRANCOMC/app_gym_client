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

        $page = $request->page ?? "";

        if(in_array($page, ["main"])) {

            $config->identityDocumentTypes = new stdClass();
            $config->identityDocumentTypes->records = IdentityDocumentType::get();

            $config->customers = new stdClass();
            $config->customers->statusses = Customer::getStatusses();

        }

        $initParams->config = $config;
        $initParams->bool   = true;

        return $initParams;

    }

    public function list(Request $request) {

        $list = Customer::when(Utilities::isDefined($request->filter_by), function($query) use($request) {

                            $filter = "%".trim($request->word ?? "")."%";

                            if(in_array($request->filter_by, ["all"])) {

                                $query->where("document_number", "like", $filter)
                                    ->orWhere("name", "like", $filter);

                            }else if(in_array($request->filter_by, ["document_number", "name"])) {

                                $query->where($request->filter_by, "like", $filter);

                            }

                        })
                        ->orderBy("name", "ASC")
                        ->with(["identityDocumentType"])
                        ->paginate($request->per_page ?? Utilities::$per_page_default);

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

        $customer = null;

        DB::transaction(function() use($request, $userAuth, &$customer) {

            $customer = new Customer();
            $customer->identity_document_type_id = $request->identity_document_type_id;
            $customer->document_number           = $request->document_number;
            $customer->name                      = $request->name;
            $customer->status                    = $request->status;
            $customer->created_at                = now();
            $customer->created_by                = $userAuth->id ?? null;
            $customer->save();

        });

        $bool = Utilities::isDefined($customer);
        $msg  = $bool ? "Cliente creado correctamente." : "No se ha podido crear el cliente.";

        return response()->json(["bool" => $bool, "msg" => $msg, "customer" => $customer], 200);

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
                            ->first();

        if(Utilities::isDefined($customer)) {

            DB::transaction(function() use($request, $userAuth, &$customer) {

                $customer->identity_document_type_id = $request->identity_document_type_id;
                $customer->document_number           = $request->document_number;
                $customer->name                      = $request->name;
                $customer->status                    = $request->status;
                $customer->updated_at                = now();
                $customer->updated_by                = $userAuth->id ?? null;
                $customer->save();

            });

        }

        $bool = Utilities::isDefined($customer);
        $msg  = $bool ? "Cliente editado correctamente." : "No se ha podido editar el cliente.";

        return response()->json(["bool" => $bool, "msg" => $msg, "customer" => $customer], 200);

    }

    public function destroy(Customer $record) {

        //

    }

}
