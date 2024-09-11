<?php

namespace App\Http\Controllers\Tenant;

use App\Helpers\Utilities;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, DB};
use stdClass;

use App\Http\Requests\Admins\Items\{StoreItemRequest, UpdateItemRequest};
use App\Models\Tenant\{Item};

class ItemController extends Controller {

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
     *
     * @param  \App\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function list(Request $request) {

        $userAuth = Auth::user();

        $list = Item::when(Utilities::isDefined($request->general), function($query) use($request) {

                        $filter = "%".trim($request->general)."%";

                        $query->where("name", "like", $filter)
                              ->orwhere("description", "like", $filter);

                    })
                    ->orderBy("name", "ASC")
                    ->orderBy("description", "ASC")
                    ->paginate(10);

        return $list;

    }

    /**
     * Display a listing of the resource.
     */
    public function index() {

        return view("Tenant/items/main");

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
    public function store(StoreItemRequest $request) {

        $item = new Item();

        DB::transaction(function() use($request, $item) {

            $userAuth = Auth::user();

            $item->name        = $request->name;
            $item->description = $request->description;
            $item->price       = $request->price;
            $item->status      = $request->status;
            $item->save();

        });

        return response()->json(["message" => "Producto - Servicio creado correctamente.", "item" => $item], 200);

    }

    /**
     * Display the specified resource.
     */
    public function show(Item $item) {

        //

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Item $item) {

        //

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateItemRequest $request, Item $item) {

        //

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Item $item) {

        //

    }

}
