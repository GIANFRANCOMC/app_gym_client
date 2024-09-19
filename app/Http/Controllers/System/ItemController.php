<?php

namespace App\Http\Controllers\System;

use App\Helpers\System\Utilities;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, DB};
use stdClass;

use App\Http\Requests\System\Items\{StoreItemRequest, UpdateItemRequest};
use App\Models\System\{Item};

class ItemController extends Controller {

    /**
     * Display init params.
     *
     * @param  \App\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function initParams(Request $request) {

        $initParams = new stdClass();

        $config = new stdClass();
        $config->items = new stdClass();
        $config->items->statusses = Item::getStatusses();

        $initParams->config = $config;
        $initParams->bool   = true;

        return $initParams;

    }

    /**
     * Display a listing of the resource.
     *
     * @param  \App\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function list(Request $request) {

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

        return view("System/items/main");

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

        $userAuth = Auth::user();

        $item = new Item();

        DB::transaction(function() use($request, $userAuth, $item) {

            $item->name        = $request->name;
            $item->description = $request->description;
            $item->price       = $request->price;
            $item->status      = $request->status;
            $item->created_at  = now();
            $item->created_by  = $userAuth->id ?? null;
            $item->save();

        });

        return response()->json(["bool" => true, "msg" => "Producto creado correctamente.", "item" => $item], 200);

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
    public function update(UpdateItemRequest $request, $id) {

        $userAuth = Auth::user();

        $item = Item::where("id", $id)
                    ->firstOrFail();

        DB::transaction(function() use($request, $userAuth, $item) {

            $item->name        = $request->name;
            $item->description = $request->description;
            $item->price       = $request->price;
            $item->status      = $request->status;
            $item->updated_at  = now();
            $item->updated_by  = $userAuth->id ?? null;
            $item->save();

        });

        return response()->json(["bool" => true, "msg" => "Producto editado correctamente.", "item" => $item], 200);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Item $item) {

        //

    }

}
