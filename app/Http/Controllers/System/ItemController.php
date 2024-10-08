<?php

namespace App\Http\Controllers\System;

use App\Helpers\System\Utilities;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, DB};
use stdClass;

use App\Http\Requests\System\Items\{StoreItemRequest, UpdateItemRequest};
use App\Models\System\{Currency, Item};

class ItemController extends Controller {

    public function initParams(Request $request) {

        $initParams = new stdClass();

        $config = new stdClass();
        $config->items = new stdClass();
        $config->items->statusses = Item::getStatusses();

        $config->currencies = new stdClass();
        $config->currencies->records = Currency::get();

        $initParams->config = $config;
        $initParams->bool   = true;

        return $initParams;

    }

    public function list(Request $request) {

        $list = Item::when(Utilities::isDefined($request->general), function($query) use($request) {

                        $filter = "%".trim($request->general)."%";

                        $query->where("name", "like", $filter)
                              ->orwhere("description", "like", $filter);

                    })
                    ->orderBy("name", "ASC")
                    ->with(["currency"])
                    ->paginate(10);

        return $list;

    }

    public function index() {

        return view("System/general/items/main");

    }

    public function create() {

        //

    }

    public function store(StoreItemRequest $request) {

        $userAuth = Auth::user();

        $item = new Item();

        DB::transaction(function() use($request, $userAuth, $item) {

            $item->name        = $request->name;
            $item->description = $request->description;
            $item->price       = $request->price;
            $item->currency_id = $request->currency_id;
            $item->status      = $request->status;
            $item->created_at  = now();
            $item->created_by  = $userAuth->id ?? null;
            $item->save();

        });

        return response()->json(["bool" => true, "msg" => "Producto creado correctamente.", "item" => $item], 200);

    }

    public function show(Item $item) {

        //

    }

    public function edit(Item $item) {

        //

    }

    public function update(UpdateItemRequest $request, $id) {

        $userAuth = Auth::user();

        $item = Item::where("id", $id)
                    ->firstOrFail();

        DB::transaction(function() use($request, $userAuth, $item) {

            $item->name        = $request->name;
            $item->description = $request->description;
            $item->price       = $request->price;
            $item->currency_id = $request->currency_id;
            $item->status      = $request->status;
            $item->updated_at  = now();
            $item->updated_by  = $userAuth->id ?? null;
            $item->save();

        });

        return response()->json(["bool" => true, "msg" => "Producto editado correctamente.", "item" => $item], 200);

    }

    public function destroy(Item $item) {

        //

    }

}
