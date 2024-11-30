<?php

namespace App\Http\Controllers\System;

use App\Helpers\System\Utilities;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, DB};
use stdClass;

use App\Http\Requests\System\Branches\{StoreBranchRequest, UpdateBranchRequest};
use App\Models\System\{Branch, DocumentType, Serie};

class BranchController extends Controller {

    public function initParams(Request $request) {

        $initParams = new stdClass();

        $config = new stdClass();

        $page = $request->page ?? "";

        if(in_array($page, ["main"])) {

            $config->branches = new stdClass();
            $config->branches->statusses = Branch::getStatusses();

        }

        $initParams->config = $config;
        $initParams->bool   = true;

        return $initParams;

    }

    public function list(Request $request) {

        $list = Branch::when(Utilities::isDefined($request->filter_by), function($query) use($request) {

                            $filter = "%".trim($request->word ?? "")."%";

                            if(in_array($request->filter_by, ["all"])) {

                                $query->Where("name", "like", $filter);

                            }else if(in_array($request->filter_by, ["name"])) {

                                $query->where($request->filter_by, "like", $filter);

                            }

                        })
                        ->orderBy("name", "ASC")
                        ->with(["series.documentType"])
                        ->paginate($request->per_page ?? Utilities::$per_page_default);

        return $list;

    }

    public function index() {

        return view("System/general/branches/main");

    }

    public function create() {

        //

    }

    public function store(StoreBranchRequest $request) {

        $userAuth = Auth::user();

        $branch = new Branch();

        DB::transaction(function() use($request, $userAuth, &$branch) {

            $branch->company_id = env("COMPANY_ID");
            $branch->name       = $request->name;
            $branch->status     = $request->status;
            $branch->created_at = now();
            $branch->created_by = $userAuth->id ?? null;
            $branch->save();

            $documentTypes = DocumentType::where("status", ["active"])
                                         ->get();

            foreach($documentTypes as $documentType) {

                $serie = new Serie();
                $serie->branch_id        = $branch->id;
                $serie->document_type_id = $documentType->id;
                $serie->code             = $documentType->code;
                $serie->number           = $branch->id;
                $serie->init             = 1;
                $serie->status           = "active";
                $serie->created_at       = now();
                $serie->created_by       = $userAuth->id ?? null;
                $serie->save();

            }

        });

        $bool = Utilities::isDefined($branch);
        $msg  = $bool ? "Sucursal creada correctamente." : "No se ha podido crear la sucursal.";

        return response()->json(["bool" => $bool, "msg" => $msg, "branch" => $branch], 200);

    }

    public function show(Branch $record) {

        //

    }

    public function edit(Branch $record) {

        //

    }

    public function update(UpdateBranchRequest $request, $id) {

        $userAuth = Auth::user();

        $branch = Branch::where("id", $id)
                        ->first();

        if(Utilities::isDefined($branch)) {

            DB::transaction(function() use($request, $userAuth, &$branch) {

                $branch->name       = $request->name;
                $branch->status     = $request->status;
                $branch->updated_at = now();
                $branch->updated_by = $userAuth->id ?? null;
                $branch->save();

            });

        }

        $bool = Utilities::isDefined($branch);
        $msg  = $bool ? "Sucursal editada correctamente." : "No se ha podido editar la sucursal.";

        return response()->json(["bool" => $bool, "msg" => $msg, "branch" => $branch], 200);

    }

    public function destroy(Branch $record) {

        //

    }

}
