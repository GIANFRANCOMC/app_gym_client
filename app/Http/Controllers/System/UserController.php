<?php

namespace App\Http\Controllers\System;

use App\Helpers\System\Utilities;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, DB};
use stdClass;

use App\Http\Requests\System\Users\{StoreUserRequest, UpdateUserRequest};
use App\Models\System\{IdentityDocumentType, Role, User};

class UserController extends Controller {

    public function initParams(Request $request) {

        $initParams = new stdClass();

        $config = new stdClass();

        $page = $request->page ?? "";

        if(in_array($page, ["main"])) {

            $config->identityDocumentTypes = new stdClass();
            $config->identityDocumentTypes->records = IdentityDocumentType::whereIn("id", [1, 2])
                                                                          ->get();

            $config->roles = new stdClass();
            $config->roles->records = Role::getAll("user");

            $config->users = new stdClass();
            $config->users->statuses = User::getStatuses();

        }

        $initParams->config = $config;
        $initParams->bool   = true;

        return $initParams;

    }

    public function list(Request $request) {

        $userAuth = Auth::user();

        $list = User::when(Utilities::isDefined($request->filter_by), function($query) use($request) {

                        $filter = Utilities::getWordSearch($request->word);

                        if(in_array($request->filter_by, ["all"])) {

                            $query->where(function($query) use($request, $filter) {

                                $query->where("document_number", "like", $filter)
                                      ->orWhere("name", "like", $filter)
                                      ->orWhere("email", "like", $filter);

                            });

                        }else if(in_array($request->filter_by, ["document_number", "name", "email"])) {

                            $query->where(function($query) use($request, $filter) {

                                $query->where($request->filter_by, "like", $filter);

                            });

                        }

                    })
                    ->where("company_id", $userAuth->company_id)
                    ->orderBy("name", "ASC")
                    ->with(["identityDocumentType", "role"])
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

        $userExists = User::where("company_id", $userAuth->company_id)
                          ->where("identity_document_type_id", $request->identity_document_type_id)
                          ->where("document_number", $request->document_number)
                          ->exists();

        if($userExists) {

            return response()->json(["bool" => false, "msg" => "El colaborador ingresado ya ha sido registrado."], 200);

        }

        DB::transaction(function() use($request, $userAuth, &$user) {

            $user = new User();
            $user->company_id                = $userAuth->company_id;
            $user->role_id                   = $request->role_id;
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
        $msg  = $bool ? "Colaborador creado correctamente." : "No se ha podido crear el colaborador.";

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
                    ->where("company_id", $userAuth->company_id)
                    ->first();

        if(Utilities::isDefined($user)) {

            $userExists = User::where("company_id", $userAuth->company_id)
                              ->where("identity_document_type_id", $request->identity_document_type_id)
                              ->where("document_number", $request->document_number)
                              ->whereNot("id", $user->id)
                              ->exists();

            if($userExists) {

                return response()->json(["bool" => false, "msg" => "El colaborador ingresado ya ha sido registrado."], 200);

            }

            DB::transaction(function() use($request, $userAuth, &$user) {

                $user->role_id                   = $request->role_id;
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
        $msg  = $bool ? "Colaborador editado correctamente." : "No se ha podido editar el colaborador.";

        return response()->json(["bool" => $bool, "msg" => $msg, "user" => $user], 200);

    }

    public function destroy(User $record) {

        //

    }

}
