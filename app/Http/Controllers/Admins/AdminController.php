<?php

namespace App\Http\Controllers\Admins;

use App\Helpers\Utilities;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Http\Requests\Admins\Admins\{StoreAdminRequest, UpdateAdminRequest};
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @param  \App\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function list(Request $request) {

        $list = Admin::when(Utilities::validateVariable($request->general), function($query) use ($request) {

                       $filter = "%".trim($request->general)."%";

                        $query->where("number_document", "like", $filter)
                              ->orwhere("last_name", "like", $filter)
                              ->orWhere("first_name", "like", $filter);

                    })
                    ->orderBy("last_name", "ASC")
                    ->orderBy("first_name", "ASC")
                    ->paginate(10);

        return $list;

    }

    /**
     * Display a listing of the resource.
     */
    public function index() {

        return view("admins/admins/main");

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
            $user->admin_id = $admin->id;
            $user->name     = $admin->last_name." ".$admin->first_name;
            $user->email    = $request->email;
            $user->password = $request->password;
            $user->save();

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
    public function update(UpdateAdminRequest $request, Admin $admin) {

        //

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Admin $admin) {

        //

    }

}
