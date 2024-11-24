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

        $list = Branch::when(Utilities::isDefined($request->general), function($query) use($request) {

                            $filter = "%".trim($request->general)."%";

                            $query->where("name", "like", $filter);

                        })
                        ->orderBy("name", "ASC")
                        ->with(["series.documentType"])
                        ->paginate(10);

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

        DB::transaction(function() use($request, $userAuth, $branch) {

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

        return response()->json(["bool" => true, "msg" => "Sucursal creada correctamente.", "branch" => $branch], 200);

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
                        ->firstOrFail();

        DB::transaction(function() use($request, $userAuth, $branch) {

            $branch->name       = $request->name;
            $branch->status     = $request->status;
            $branch->updated_at = now();
            $branch->updated_by = $userAuth->id ?? null;
            $branch->save();

        });

        return response()->json(["bool" => true, "msg" => "Sucursal editada correctamente.", "branch" => $branch], 200);

    }

    public function destroy(Branch $record) {

        //

    }

}
