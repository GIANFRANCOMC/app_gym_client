<?php

namespace App\Http\Controllers\System;

use App\Helpers\System\Utilities;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, DB};
use stdClass;

use App\Http\Requests\System\Companies\{StoreCompanyRequest, UpdateCompanyRequest};
use App\Models\System\{Company, IdentityDocumentType};

class CompanyController extends Controller {

    public function initParams(Request $request) {

        $userAuth = Auth::user();

        $initParams = new stdClass();

        $config = new stdClass();

        $page = $request->page ?? "";

        if(in_array($page, ["main"])) {

            $config->companies = new stdClass();
            $config->companies->statuses = Company::getStatuses();

            $config->company = new stdClass();
            $config->company->records = [$userAuth->company];

            $config->identityDocumentTypes = new stdClass();
            $config->identityDocumentTypes->records = IdentityDocumentType::getAll("company");

        }

        $initParams->config = $config;
        $initParams->bool   = true;

        return $initParams;

    }

    public function list(Request $request) {

        //

    }

    public function index() {

        return view("System/general/companies/main");

    }

    public function create() {

        //

    }

    public function store(StoreCompanyRequest $request) {

        //

    }

    public function show(Company $company) {

        //

    }

    public function edit(Company $company) {

        //

    }

    public function update(UpdateCompanyRequest $request, $id) {

        $userAuth = Auth::user();

        $company = Company::where("id", $userAuth->company_id)
                          ->first();

        if(Utilities::isDefined($company)) {

            DB::transaction(function() use($request, $userAuth, &$company) {

                $company->identity_document_type_id = $request->identity_document_type_id;
                $company->document_number           = $request->document_number;
                $company->legal_name                = $request->legal_name;
                $company->commercial_name           = $request->commercial_name;
                $company->address                   = $request->address;
                $company->telephone                 = $request->telephone;
                $company->email                     = $request->email;
                $company->token_api_misc            = $request->token_api_misc;
                // $company->status                    = $request->status;
                $company->updated_at                = now();
                $company->updated_by                = $userAuth->id ?? null;
                $company->save();

            });

        }

        $bool = Utilities::isDefined($company);
        $msg  = $bool ? "Empresa editada correctamente." : "No se ha podido editar la empresa.";

        return response()->json(["bool" => $bool, "msg" => $msg, "company" => $company], 200);

    }

    public function destroy(Company $company) {

        //

    }

}
