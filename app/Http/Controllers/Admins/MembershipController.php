<?php

namespace App\Http\Controllers\Admins;

use App\Helpers\Utilities;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, DB};
use stdClass;

use App\Http\Requests\Admins\Memberships\{StoreMembershipRequest, UpdateMembershipRequest};
use App\Models\{Membership};

class MembershipController extends Controller {

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
     *
     * @param  \App\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function list(Request $request) {

        $userAuth = Auth::user();

        $list = Membership::when(Utilities::validateVariable($request->general), function($query) use($request) {

                        $filter = "%".trim($request->general)."%";

                        $query->where("name", "like", $filter)
                              ->orwhere("description", "like", $filter);

                    })
                    ->where("company_id", $userAuth->company_id)
                    ->orderBy("name", "ASC")
                    ->orderBy("description", "ASC")
                    ->paginate(10);

        return $list;

    }

    /**
     * Display a listing of the resource.
     */
    public function index() {

        return view("admins/memberships/main");

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
    public function store(StoreMembershipRequest $request) {

        $membership = new Membership();

        DB::transaction(function() use($request, $membership) {

            $userAuth = Auth::user();

            $membership->name              = $request->name;
            $membership->description       = $request->description;
            $membership->duration_quantity = $request->duration_quantity;
            $membership->type              = $request->type;
            $membership->price             = $request->price;
            $membership->company_id        = $userAuth->company_id;
            $membership->status            = $request->status;
            $membership->save();

        });

        return response()->json(["message" => "Membresía creada correctamente.", "membership" => $membership], 200);

    }

    /**
     * Display the specified resource.
     */
    public function show(Membership $membership) {

        //

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Membership $membership) {

        //

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMembershipRequest $request, Membership $membership) {

        //

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Membership $membership) {

        //

    }

}
