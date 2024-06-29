<?php

namespace App\Http\Controllers\Admins;

use App\Helpers\Utilities;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, DB};
use stdClass;

use App\Http\Requests\Admins\Admins\{StoreAdminRequest, UpdateAdminRequest};
use App\Models\Tenant\{Admin, Branch, BranchUser, User};

class AdminController extends Controller {

    /**
     * Display init params.
     *
     * @param  \App\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function initParams(Request $request) {

        $userAuth = Auth::user();

        $initParams = new stdClass();

        $branches = Branch::where("status", "active")
                          ->orderBy("name", "ASC")
                          ->orderBy("location", "ASC")
                          ->get();

        $configs = [
            "admins" => [
                "documentsType" => [
                    ["id" => "dni", "name" => "DNI"]
                ],
                "genders" => [
                    ["id" => "male", "name" => "Masculino"],
                    ["id" => "female", "name" => "Femenino"],
                    ["id" => "other", "name" => "Otro"]
                ],
                "statuses" => [
                    ["id" => "active", "name" => "Activo"],
                    ["id" => "inactive", "name" => "Inactivo"]
                ],
                "branches" => $branches
            ]
        ];

        $initParams->configs  = $configs;

        return $initParams;

    }

    /**
     * Display a listing of the resource.
     *
     * @param  \App\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function list(Request $request) {

        $userAuth = Auth::user();

        $list = Admin::when(Utilities::validateVariable($request->general), function($query) use($request) {

                        $filter = "%".trim($request->general)."%";

                        $query->where("number_document", "like", $filter)
                              ->orwhere("last_name", "like", $filter)
                              ->orWhere("first_name", "like", $filter);

                    })
                    ->orderBy("last_name", "ASC")
                    ->orderBy("first_name", "ASC")
                    ->with(["user.branchUsers"])
                    ->paginate(10);

        return $list;

    }

    /**
     * Display a listing of the resource.
     */
    public function index() {

        return view("tenant/admins/admins/main");

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
    public function store(StoreAdminRequest $request) {

        $admin = new Admin();

        DB::transaction(function() use($request, $admin) {

            $userAuth = Auth::user();

            $admin->type_document   = $request->type_document;
            $admin->number_document = $request->number_document;
            $admin->last_name       = $request->last_name;
            $admin->first_name      = $request->first_name;
            $admin->birth_date      = $request->birth_date;
            $admin->gender          = $request->gender;
            $admin->phone           = $request->phone;
            $admin->status          = $request->status;
            $admin->save();

            $user = new User();
            $user->name       = $admin->last_name." ".$admin->first_name;
            $user->email      = $request->email;
            $user->password   = $request->password;
            $user->admin_id   = $admin->id;
            $user->status     = "active";
            $user->save();

            if(Utilities::validateVariable($request->branches) && count($request->branches) > 0) {

                $branchesId = array_values(array_unique($request->branches));

                foreach($branchesId as $branchId) {

                    $branch = Branch::where("id", $branchId)
                                    ->where("status", "active")
                                    ->first();

                    if(Utilities::validateVariable($branch)) {

                        $branchUser = new BranchUser();
                        $branchUser->branch_id = $branch->id;
                        $branchUser->user_id   = $user->id;
                        $branchUser->status    = "active";
                        $branchUser->save();

                    }

                }

            }

        });

        return response()->json(["message" => "Colaborador creado correctamente.", "admin" => $admin], 200);

    }

    /**
     * Display the specified resource.
     */
    public function show(Admin $admin) {

        //

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Admin $admin) {

        //

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAdminRequest $request, $id) {

        $admin = Admin::find($id);
        $admin->type_document   = $request->type_document;
        $admin->number_document = $request->number_document;
        $admin->save();

        return response()->json(["message" => "Colaborador editado correctamente.", "admin" => $admin], 200);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Admin $admin) {

        //

    }

}
