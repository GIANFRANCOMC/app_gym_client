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

            $config->assets = new stdClass();
            $config->assets->records = Asset::getAll("asset_management");

            $config->branches = new stdClass();
            $config->branches->records = Branch::getAll("asset_management");

        }

        $initParams->config = $config;
        $initParams->bool   = true;

        return $initParams;

    }

    public function list(Request $request) {

        $userAuth = Auth::user();

        $branchExists = Branch::where("id", $request->branch_id)
                              ->where("company_id", $userAuth->company_id)
                              ->exists();

        if(!$branchExists) {

            return new LengthAwarePaginator([], 0, 1, 1, ["path" => ""]);

        }

        $list = BranchAsset::select("branch_assets.*")
                           ->join("assets", "assets.id", "=", "branch_assets.asset_id")
                           ->where("branch_id", $request->branch_id)
                           // ->whereIn("status", ["active"])
                           ->with(["asset"])
                           ->orderBy("assets.name", "ASC")
                           ->paginate($request->per_page ?? Utilities::$per_page_max);

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

        $branchExists = Branch::where("id", $request->branch_id)
                              ->where("company_id", $userAuth->company_id)
                              // ->whereIn("status", ["active"])
                              ->exists();

        $branchItems = array_unique(array_column($request->items, "branch_id"));

        if(!$branchExists) {

            return response()->json(["bool" => false, "msg" => "La sucursal seleccionada no se encuentra disponible."], 200);

        }else if(count($branchItems) != 1) {

            return response()->json(["bool" => false, "msg" => "Hay más de una sucursal seleccionada."], 200);

        }else if(!in_array($request->branch_id, $branchItems)) {

            return response()->json(["bool" => false, "msg" => "La sucursal seleccionada no coincide con los registros ingresados."], 200);

        }

        DB::transaction(function() use($request, $userAuth) {

            foreach($request->items as $item) {

                $branchAsset = null;

                if(floatval($item["id"]) > 0) {

                    $branchAsset = BranchAsset::where("id", $item["id"])
                                              ->first();

                }

                if(Utilities::isDefined($branchAsset)) {

                    $branchAsset->quantity          = Utilities::round(floatval($item["quantity"] ?? 0));
                    $branchAsset->acquisition_value = Utilities::round(floatval($item["acquisition_value"] ?? 0));
                    $branchAsset->acquisition_date  = $item["acquisition_date"] ?? null;
                    $branchAsset->notes             = $item["notes"] ?? "";
                    $branchAsset->status            = $item["status"];
                    $branchAsset->updated_at        = now();
                    $branchAsset->updated_by        = $userAuth->id ?? null;
                    $branchAsset->save();

                }else {

                    $branchAsset = new BranchAsset();
                    $branchAsset->branch_id         = $item["branch_id"];
                    $branchAsset->asset_id          = $item["asset_id"];
                    $branchAsset->quantity          = Utilities::round(floatval($item["quantity"] ?? 0));
                    $branchAsset->acquisition_value = Utilities::round(floatval($item["acquisition_value"] ?? 0));
                    $branchAsset->acquisition_date  = $item["acquisition_date"] ?? null;
                    $branchAsset->notes             = $item["notes"] ?? "";
                    $branchAsset->status            = $item["status"];
                    $branchAsset->created_at        = now();
                    $branchAsset->created_by        = $userAuth->id ?? null;
                    $branchAsset->save();

                }

            }

        });

        $bool = true;
        $msg  = $bool ? "Activos actualizados correctamente." : "No se ha podido actualizar los activos.";

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
