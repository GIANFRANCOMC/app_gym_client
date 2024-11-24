<?php

namespace App\Http\Controllers\System;

use App\Helpers\System\Utilities;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, DB};
use stdClass;

use App\Http\Requests\System\Users\{StoreUserRequest, UpdateUserRequest};
use App\Models\System\{IdentityDocumentType, User};

class UserController extends Controller {

    public function initParams(Request $request) {

        $initParams = new stdClass();

        $config = new stdClass();

        $page = $request->page ?? "";

        if(in_array($page, ["main"])) {

            $config->identityDocumentTypes = new stdClass();
            $config->identityDocumentTypes->records = IdentityDocumentType::get();

            $config->users = new stdClass();
            $config->users->statusses = User::getStatusses();

        }

        $initParams->config = $config;
        $initParams->bool   = true;

        return $initParams;

    }

    public function list(Request $request) {

        $list = User::when(Utilities::isDefined($request->general), function($query) use($request) {

                            $filter = "%".trim($request->general)."%";

                            $query->where("name", "like", $filter);

                    })
                    ->orderBy("name", "ASC")
                    ->with(["identityDocumentType"])
                    ->paginate(10);

        return $list;

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
