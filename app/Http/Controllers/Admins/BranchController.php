<?php

namespace App\Http\Controllers\Admins;

use App\Helpers\Utilities;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, DB};
use stdClass;

use App\Http\Requests\Admins\Branches\{StoreBranchRequest, UpdateBranchRequest};
use App\Models\{Branch};

class BranchController extends Controller {

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

        $list = Branch::when(Utilities::validateVariable($request->general), function($query) use($request) {

                        $filter = "%".trim($request->general)."%";

                        $query->where("name", "like", $filter)
                              ->orwhere("location", "like", $filter);

                    })
                    ->orderBy("name", "ASC")
                    ->orderBy("location", "ASC")
                    ->paginate(10);

        return $list;

    }

    /**
     * Display a listing of the resource.
     */
    public function index() {

        return view("admins/branches/main");

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
    public function store(StoreBranchRequest $request) {

        $branch = new Branch();

        DB::transaction(function() use($request, $branch) {

            $userAuth = Auth::user();

            $branch->name       = $request->name;
            $branch->location   = $request->location;
            $branch->status     = $request->status;
            $branch->save();

        });

        return response()->json(["message" => "Sucursal creada correctamente.", "branch" => $branch], 200);

    }

    /**
     * Display the specified resource.
     */
    public function show(Branch $branch) {

        //

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Branch $branch) {

        //

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBranchRequest $request, Branch $branch) {

        //

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Branch $branch) {

        //

    }

}
