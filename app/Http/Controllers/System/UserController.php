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

        $list = User::when(Utilities::isDefined($request->filter_by), function($query) use($request) {

                        $filter = "%".trim($request->word ?? "")."%";

                        if(in_array($request->filter_by, ["all"])) {

                            $query->where("document_number", "like", $filter)
                                  ->orWhere("name", "like", $filter)
                                  ->orWhere("email", "like", $filter);

                        }else if(in_array($request->filter_by, ["document_number", "name" , "email"])) {

                            $query->where($request->filter_by, "like", $filter);

                        }

                    })
                    ->orderBy("name", "ASC")
                    ->with(["identityDocumentType"])
                    ->paginate($request->per_page ?? Utilities::$per_page_default);

        return $list;

    }

    public function index() {

        return view("System/general/users/main");

    }

    public function create() {

        //

    }

    public function store(StoreUserRequest $request) {

        $userAuth = Auth::user();

        $user = null;

        DB::transaction(function() use($request, $userAuth, &$user) {

            $user = new User();
            $user->identity_document_type_id = $request->identity_document_type_id;
            $user->document_number           = $request->document_number;
            $user->name                      = $request->name;
            $user->email                     = $request->email;
            $user->password                  = $request->password;
            $user->status                    = $request->status;
            $user->created_at                = now();
            $user->created_by                = $userAuth->id ?? null;
            $user->save();

        });

        $bool = Utilities::isDefined($user);
        $msg  = $bool ? "Usuario creado correctamente." : "No se ha podido crear el usuario.";

        return response()->json(["bool" => $bool, "msg" => $msg, "user" => $user], 200);

    }

    public function show(User $record) {

        //

    }

    public function edit(User $record) {

        //

    }

    public function update(UpdateUserRequest $request, $id) {

        $userAuth = Auth::user();

        $user = User::where("id", $id)
                    ->first();

        if(Utilities::isDefined($user)) {

            DB::transaction(function() use($request, $userAuth, &$user) {

                $user->identity_document_type_id = $request->identity_document_type_id;
                $user->document_number           = $request->document_number;
                $user->name                      = $request->name;
                $user->email                     = $request->email;
                $user->status                    = $request->status;
                $user->updated_at                = now();
                $user->updated_by                = $userAuth->id ?? null;

                if(Utilities::isDefined($request->password)) {

                    $user->password = $request->password;

                }

                $user->save();

            });

        }

        $bool = Utilities::isDefined($user);
        $msg  = $bool ? "Usuario editado correctamente." : "No se ha podido editar el usuario.";

        return response()->json(["bool" => $bool, "msg" => $msg, "user" => $user], 200);

    }

    public function destroy(User $record) {

        //

    }

}
