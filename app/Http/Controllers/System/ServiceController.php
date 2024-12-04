<?php

namespace App\Http\Controllers\System;

use App\Helpers\System\Utilities;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, DB};
use stdClass;

use App\Http\Requests\System\Services\{StoreServiceRequest, UpdateServiceRequest};
use App\Models\System\{Currency, Item};

class ServiceController extends Controller {

    public function initParams(Request $request) {

        $initParams = new stdClass();

        $config = new stdClass();

        $page = $request->page ?? "";

        if(in_array($page, ["main"])) {

            $config->services = new stdClass();
            $config->services->statusses = Item::getStatusses();

            $config->currencies = new stdClass();
            $config->currencies->records = Currency::get();

        }

        $initParams->config = $config;
        $initParams->bool   = true;

        return $initParams;

    }

    public function list(Request $request) {

        $userAuth = Auth::user();

        $list = Item::when(Utilities::isDefined($request->filter_by), function($query) use($request) {

                        $filter = "%".trim($request->word ?? "")."%";

                        if(in_array($request->filter_by, ["all"])) {

                            $query->where(function($query) use($request, $filter) {

                                $query->where("internal_code", "like", $filter)
                                      ->orWhere("name", "like", $filter)
                                      ->orWhere("description", "like", $filter)
                                      ->orWhere("price", "like", $filter);

                            });

                        }else if(in_array($request->filter_by, ["internal_code", "name", "description", "price"])) {

                            $query->where(function($query) use($request, $filter) {

                                $query->where($request->filter_by, "like", $filter);

                            });

                        }

                    })
                    ->where("company_id", $userAuth->company_id)
                    ->whereIn("type", ["service"])
                    ->orderBy("name", "ASC")
                    ->with(["currency"])
                    ->paginate($request->per_page ?? Utilities::$per_page_default);

        return $list;

    }

    public function index() {

        return view("System/general/services/main");

    }

    public function create() {

        //

    }

    public function store(StoreServiceRequest $request) {

        $userAuth = Auth::user();

        $item = null;

        $internalCodeExists = Item::where("company_id", $userAuth->company_id)
                                  ->where("internal_code", $request->internal_code)
                                  ->exists();

        if($internalCodeExists) {

            return response()->json(["bool" => false, "msg" => "El código interno ya ha sido registrado", "item" => null], 200);

        }

        DB::transaction(function() use($request, $userAuth, &$item) {

            $item = new Item();
            $item->company_id    = $userAuth->company_id;
            $item->internal_code = $request->internal_code;
            $item->name          = $request->name;
            $item->description   = $request->description;
            $item->price         = $request->price;
            $item->currency_id   = $request->currency_id;
            $item->type          = "service";
            $item->status        = $request->status;
            $item->created_at    = now();
            $item->created_by    = $userAuth->id ?? null;
            $item->save();

        });

        $bool = Utilities::isDefined($item);
        $msg  = $bool ? "Servicio creado correctamente." : "No se ha podido crear el servicio.";

        return response()->json(["bool" => $bool, "msg" => $msg, "item" => $item], 200);

    }

    public function show(Item $item) {

        //

    }

    public function edit(Item $item) {

        //

    }

    public function update(UpdateServiceRequest $request, $id) {

        $userAuth = Auth::user();

        $item = Item::where("id", $id)
                    ->first();

        if(Utilities::isDefined($item)) {

            $internalCodeExists = Item::where("company_id", $userAuth->company_id)
                                      ->where("internal_code", $request->internal_code)
                                      ->whereNot("id", $item->id)
                                      ->exists();

            if($internalCodeExists) {

                return response()->json(["bool" => false, "msg" => "El código interno ya ha sido registrado", "item" => null], 200);

            }

            DB::transaction(function() use($request, $userAuth, &$item) {

                $item->internal_code = $request->internal_code;
                $item->name          = $request->name;
                $item->description   = $request->description;
                $item->price         = $request->price;
                $item->currency_id   = $request->currency_id;
                $item->status        = $request->status;
                $item->updated_at    = now();
                $item->updated_by    = $userAuth->id ?? null;
                $item->save();

            });

        }

        $bool = Utilities::isDefined($item);
        $msg  = $bool ? "Servicio editado correctamente." : "No se ha podido editar el servicio.";

        return response()->json(["bool" => $bool, "msg" => $msg, "item" => $item], 200);

    }

    public function destroy(Item $item) {

        //

    }

}
