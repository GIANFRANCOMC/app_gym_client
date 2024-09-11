<?php

namespace App\Http\Controllers\Tenant;

use App\Helpers\Utilities;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, DB};
use stdClass;

use App\Http\Requests\Admins\Companies\{StoreCompanyRequest, UpdateCompanyRequest};
use App\Models\Tenant\{Company};

class CompanyController extends Controller {

    /**
     * Display init params.
     *
     * @param  \App\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function initParams(Request $request) {

        $userAuth = Auth::user();

        $initParams = new stdClass();
        $initParams->bool = true;

        return $initParams;

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
    public function store(StoreCompanyRequest $request) {

        //

    }

    /**
     * Display the specified resource.
     */
    public function show(Company $company) {

        //

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Company $company) {

        //

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCompanyRequest $request, Company $company) {

        //

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Company $company) {

        //

    }

}
