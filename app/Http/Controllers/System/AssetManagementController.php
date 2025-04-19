<?php

namespace App\Http\Controllers\System;

use App\Helpers\System\Utilities;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, DB};
use stdClass;

use App\Models\System\{Asset, Branch, BranchAsset};
use Illuminate\Pagination\LengthAwarePaginator;

class AssetManagementController extends Controller {

    public function initParams(Request $request) {

        $initParams = new stdClass();

        $config = new stdClass();

        $page = $request->page ?? "";

        if(in_array($page, ["main"])) {

            $config->branches = new stdClass();
            $config->branches->records = Branch::getAll("asset_management");

        }

        $initParams->config = $config;
        $initParams->bool   = true;

        return $initParams;

    }

    public function list(Request $request) {

        $userAuth = Auth::user();

        $branch = Branch::where("id", $request->branch_id)
                        ->where("company_id", $userAuth->company_id)
                        ->first();

        if(!Utilities::isDefined($branch)) {

            return new LengthAwarePaginator([], 0, 1, 1, ["path" => ""]);

        }

        $list = Asset::leftJoin("branch_assets", function($join) use($branch) {

                        $join->on("branch_assets.asset_id", "=", "assets.id")
                             ->where("branch_assets.branch_id", $branch->id);

                      })
                      ->leftJoin("currencies", function($join) use($branch) {

                        $join->on("currencies.id", "=", "branch_assets.currency_id");

                      })
                      ->select(
                        "assets.id AS asset_id",
                        "assets.name AS asset_name",
                        "assets.description AS asset_description",
                        "assets.internal_code AS asset_internal_code",
                        DB::raw('COALESCE(branch_assets.id, "") as branch_asset_id'),
                        DB::raw('COALESCE(currencies.sign, "") as currencies_sign'),
                        DB::raw('COALESCE(branch_assets.quantity, 0) as branch_asset_quantity'),
                        DB::raw('COALESCE(branch_assets.acquisition_value, 0) as branch_asset_acquisition_value'),
                        DB::raw('COALESCE(branch_assets.acquisition_date, "") as branch_asset_acquisition_date'),
                        DB::raw('COALESCE(branch_assets.note, "") as branch_asset_note'),
                        DB::raw('COALESCE(branch_assets.status, "active") as branch_asset_status')
                      )
                      ->orderBy("assets.name", "ASC")
                      ->paginate($request->per_page ?? Utilities::$per_page_max);

        $list->getCollection()->transform(function($item) use($branch) {

            $item->branch_id = $branch->id;

            if(!Utilities::isDefined($item->branch_asset_id)) {

                $item->branch_asset_id                = Utilities::generateCode(15);
                $item->branch_asset_quantity          = 0;
                $item->branch_asset_acquisition_value = 0;
                $item->branch_asset_acquisition_date  = "";
                $item->branch_asset_note              = "";
                $item->branch_asset_status            = "active";

            }

            return $item;

        });

        return $list;

    }

    public function index() {

        return view("System/general/assets_management/main");

    }

    public function create() {

        //

    }

    public function store(Request $request) {

        $userAuth = Auth::user();

        $branch = Branch::where("id", $request->branch_id)
                        ->where("company_id", $userAuth->company_id)
                        ->first();

        $branchItems = array_unique(array_column($request->items, "branch_id"));

        if(!Utilities::isDefined($branch)) {

            return response()->json(["bool" => false, "msg" => "La sucursal seleccionada no se encuentra disponible."], 200);

        }else if(count($branchItems) != 1) {

            return response()->json(["bool" => false, "msg" => "Hay más de una sucursal seleccionada."], 200);

        }else if(!in_array($branch->id, $branchItems)) {

            return response()->json(["bool" => false, "msg" => "La sucursal seleccionada no coincide con los registros ingresados."], 200);

        }

        DB::transaction(function() use($request, $userAuth) {

            foreach($request->items as $item) {

                $branchAsset = null;

                if(floatval($item["branch_asset_id"]) > 0) {

                    $branchAsset = BranchAsset::where("id", $item["branch_asset_id"])
                                              ->first();

                }

                if(Utilities::isDefined($branchAsset)) {

                    $branchAsset->quantity          = Utilities::round(floatval($item["branch_asset_quantity"] ?? 0));
                    $branchAsset->acquisition_value = Utilities::round(floatval($item["branch_asset_acquisition_value"] ?? 0));
                    $branchAsset->acquisition_date  = $item["branch_asset_acquisition_date"] ?? null;
                    $branchAsset->note              = $item["branch_asset_note"] ?? "";
                    // $branchAsset->status            = $item["branch_asset_status"];
                    $branchAsset->updated_at        = now();
                    $branchAsset->updated_by        = $userAuth->id ?? null;
                    $branchAsset->save();

                }else {

                    $branchAsset = new BranchAsset();
                    $branchAsset->branch_id         = $item["branch_id"];
                    $branchAsset->asset_id          = $item["asset_id"];
                    $branchAsset->currency_id       = 1;
                    $branchAsset->quantity          = Utilities::round(floatval($item["branch_asset_quantity"] ?? 0));
                    $branchAsset->acquisition_value = Utilities::round(floatval($item["branch_asset_acquisition_value"] ?? 0));
                    $branchAsset->acquisition_date  = $item["branch_asset_acquisition_date"] ?? null;
                    $branchAsset->note              = $item["branch_asset_notes"] ?? "";
                    $branchAsset->status            = "active"; // $item["branch_asset_status"];
                    $branchAsset->created_at        = now();
                    $branchAsset->created_by        = $userAuth->id ?? null;
                    $branchAsset->save();

                }

            }

        });

        $bool = true;
        $msg  = $bool ? "Gestión de activos actualizados correctamente." : "No se ha podido actualizar la gestión de activos.";

        return response()->json(["bool" => $bool, "msg" => $msg], 200);

    }

    public function show($record) {

        //

    }

    public function edit($record) {

        //

    }

    public function update(Request $request, $id) {

        //

    }

    public function destroy($record) {

        //

    }

}
