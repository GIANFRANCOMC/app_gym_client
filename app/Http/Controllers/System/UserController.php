<?php

namespace App\Http\Controllers\System;

use App\Helpers\System\Utilities;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, DB};
use stdClass;

use App\Http\Requests\System\Users\{StoreUserRequest, UpdateUserRequest};
use App\Models\System\{User};

class UserController extends Controller {

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

        return view("System/general/users/main");

    }

    public function create() {

        //

    }

    public function store(StoreUserRequest $request) {

        //

    }

    public function show(User $record) {

        //

    }

    public function edit(User $record) {

        //

    }

    public function update(UpdateUserRequest $request, $id) {

        //

    }

    public function destroy(User $record) {

        //

    }

}
