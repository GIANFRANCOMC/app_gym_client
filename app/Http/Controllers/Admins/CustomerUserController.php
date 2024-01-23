<?php

namespace App\Http\Controllers\Admins;

use App\Helpers\Utilities;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, DB};
use stdClass;

use App\Http\Requests\Admins\CustomerUsers\{StoreCustomerUserRequest, UpdateCustomerUserRequest};
use App\Models\{CustomerUser};

class CustomerUserController extends Controller {

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
    public function store(StoreCustomerUserRequest $request) {

        //

    }

    /**
     * Display the specified resource.
     */
    public function show(CustomerUser $customerUser) {

        //

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CustomerUser $customerUser) {

        //

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCustomerUserRequest $request, CustomerUser $customerUser) {

        //

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CustomerUser $customerUser) {

        //

    }

}
