<?php

namespace App\Http\Controllers\System\Assets;

use App\Helpers\System\Utilities;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, DB};
use stdClass;

use App\Http\Requests\System\Assets\AssetManagements\{AssignAssetToBranchRequest, UnassignAssetFromBranchRequest};
use App\Models\System\Assets\{Asset, AssetAssignment, BranchAsset};
use App\Models\System\Auth\{User};
use App\Models\System\Organizations\{Branch};
use Illuminate\Pagination\LengthAwarePaginator;

class AssetManagementController extends Controller {

    public function initParams(Request $request) {

        $userAuth = Auth::user();

        $initParams = new stdClass();

        $config = new stdClass();

        $page = $request->page ?? "";

        if(in_array($page, ["main"])) {

            $config->assets = new stdClass();
            $config->assets->records = Asset::getAll("asset_management", $userAuth->company_id);

            $config->branches = new stdClass();
            $config->branches->records = Branch::getAll("asset_management", $userAuth->company_id);

            $config->branchAssets = new stdClass();
            $config->branchAssets->statuses = BranchAsset::getStatuses();

            $config->users = new stdClass();
            $config->users->records = User::getAll("asset_management", $userAuth->company_id);

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


        $list = BranchAsset::where("branch_id", $branch->id)
                           ->with(["asset"])
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
                    $branchAsset->status            = $item["branch_asset_status"] ?? "active";
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
                    $branchAsset->status            = $item["branch_asset_status"] ?? "active";
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

    public function assignAssetToBranch(AssignAssetToBranchRequest $request) {

        $userAuth = Auth::user();

        $branch = Asset::where("id", $request->branch_id)
                       ->where("company_id", $userAuth->company_id)
                       ->first();

        $information = [
            "success" => [
                "counter" => 0,
                "data" => []
            ],
            "error" => [
                "counter" => 0,
                "data" => []
            ]
        ];

        if(Utilities::isDefined($branch)) {

            DB::transaction(function() use($request, $userAuth, $branch, &$information) {

                foreach($request->items as $item) {

                    $branchAsset = BranchAsset::where("branch_id", $branch->id)
                                              ->where("asset_id", $item["asset_id"])
                                              ->first();

                    if(!Utilities::isDefined($branchAsset)) {

                        $branchAsset = new BranchAsset();
                        $branchAsset->branch_id         = $branch->id;
                        $branchAsset->asset_id          = $item["asset_id"];
                        $branchAsset->currency_id       = 1;
                        $branchAsset->quantity          = 0;
                        $branchAsset->acquisition_value = 0;
                        $branchAsset->acquisition_date  = null;
                        $branchAsset->note              = "";
                        $branchAsset->status            = "active";
                        $branchAsset->created_at        = now();
                        $branchAsset->created_by        = $userAuth->id ?? null;
                        $branchAsset->save();

                        $information["success"]["counter"]++;
                        $information["success"]["data"]["asset_id"] = $item["asset_id"];

                    }else {

                        if(Utilities::isDefined($branchAsset) && in_array($branchAsset->status, ["retired"])) {

                            $branchAsset->status     = "active";
                            $branchAsset->updated_at = now();
                            $branchAsset->updated_by = $userAuth->id ?? null;
                            $branchAsset->save();

                            $information["success"]["counter"]++;
                            $information["success"]["data"]["asset_id"] = $item["asset_id"];

                        }else {

                            $information["error"]["counter"]++;
                            $information["error"]["data"]["asset_id"] = $item["asset_id"];

                        }

                    }

                }

            });

        }

        $bool = $information["success"]["counter"] > 0;
        $msg  = $bool ? "Activo asociado a la sucursal correctamente." : "No se ha podido asociar el activo a la sucursal.";

        return response()->json(["bool" => $bool, "msg" => $msg, "information" => $information], 200);

    }

    public function unassignAssetFromBranch(UnassignAssetFromBranchRequest $request) {

        $userAuth = Auth::user();

        $branch = Asset::where("id", $request->branch_id)
                       ->where("company_id", $userAuth->company_id)
                       ->first();

        $information = [
            "success" => [
                "counter" => 0,
                "data" => []
            ],
            "error" => [
                "counter" => 0,
                "data" => []
            ]
        ];

        if(Utilities::isDefined($branch)) {

            DB::transaction(function() use($request, $userAuth, $branch, &$information) {

                foreach($request->items as $item) {

                    $branchAsset = BranchAsset::where("branch_id", $branch->id)
                                              ->where("asset_id", $item["asset_id"])
                                              ->whereIn("status", ["active", "maintenance"])
                                              ->first();

                    if(Utilities::isDefined($branchAsset)) {

                        $branchAsset->status     = "retired";
                        $branchAsset->updated_at = now();
                        $branchAsset->updated_by = $userAuth->id ?? null;
                        $branchAsset->save();

                        $information["success"]["counter"]++;
                        $information["success"]["data"]["asset_id"] = $item["asset_id"];

                    }else {

                        $information["error"]["counter"]++;
                        $information["error"]["data"]["asset_id"] = $item["asset_id"];

                    }

                }

            });

        }

        $bool = $information["success"]["counter"] > 0;
        $msg  = $bool ? "Activo desasociado a la sucursal correctamente." : "No se ha podido desasociar el activo a la sucursal.";

        return response()->json(["bool" => $bool, "msg" => $msg, "information" => $information], 200);

    }

    public function assign(Request $request) {

        $userAuth = Auth::user();

        $branchAsset = BranchAsset::where("id", $request->id)
                                  ->first();

        if(Utilities::isDefined($branchAsset)) {

            DB::transaction(function() use($request, $userAuth, $branchAsset) {

                $branchAsset->quantity          = $request->quantity;
                $branchAsset->acquisition_value = $request->acquisition_value;
                $branchAsset->acquisition_date  = $request->acquisition_date;
                $branchAsset->note              = $request->note;
                $branchAsset->updated_at        = now();
                $branchAsset->updated_by        = $userAuth->id ?? null;
                $branchAsset->save();

            });

        }

        $bool = Utilities::isDefined($branchAsset);
        $msg  = $bool ? "Activo editado correctamente." : "No se ha podido editar el activo.";

        return response()->json(["bool" => $bool, "msg" => $msg, "branchAsset" => $branchAsset], 200);

    }

    public function assignToUser(Request $request) {

        $userAuth = Auth::user();

        $assetAssignment = AssetAssignment::where("branch_id", $request->branch_id)
                                          ->where("asset_id", $request->asset_id)
                                          ->first();

        if(Utilities::isDefined($assetAssignment)) {

            DB::transaction(function() use($request, $userAuth, &$assetAssignment) {

                $assetAssignment->user_id    = $request->user_id;
                $assetAssignment->updated_at = now();
                $assetAssignment->updated_by = $userAuth->id ?? null;
                $assetAssignment->save();

            });

        }else {

            DB::transaction(function() use($request, $userAuth, &$assetAssignment) {

                $assetAssignment = new AssetAssignment();
                $assetAssignment->branch_id  = $request->branch_id;
                $assetAssignment->asset_id   = $request->asset_id;
                $assetAssignment->user_id    = $request->user_id;
                $assetAssignment->currency_id = 1;
                $assetAssignment->created_at = now();
                $assetAssignment->created_by = $userAuth->id ?? null;
                $assetAssignment->save();

            });

        }

        $bool = Utilities::isDefined($assetAssignment);
        $msg  = $bool ? "Activo asignado al usuario correctamente." : "No se ha podido asignar el activo al usuario.";

        return response()->json(["bool" => $bool, "msg" => $msg, "assetAssignment" => $assetAssignment], 200);

    }

}
