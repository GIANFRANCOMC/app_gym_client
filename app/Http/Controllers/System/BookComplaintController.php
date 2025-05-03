<?php

namespace App\Http\Controllers\System;

use App\Helpers\System\Utilities;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, DB};
use stdClass;

use App\Http\Requests\System\Assets\{StoreAssetRequest, UpdateAssetRequest};
use App\Models\System\{Asset, BookComplaint};

class BookComplaintController extends Controller {

    public function initParams(Request $request) {

        $initParams = new stdClass();

        $config = new stdClass();

        $page = $request->page ?? "";

        if(in_array($page, ["main"])) {

            $config->bookComplaints = new stdClass();
            $config->bookComplaints->types    = BookComplaint::getTypes();
            $config->bookComplaints->statuses = BookComplaint::getStatuses();

        }

        $initParams->config = $config;
        $initParams->bool   = true;

        return $initParams;

    }

    public function list(Request $request) {

        $userAuth = Auth::user();

        $list = BookComplaint::when(Utilities::isDefined($request->filter_by), function($query) use($request) {

                                $filter = Utilities::getWordSearch($request->word);

                                if(in_array($request->filter_by, ["all"])) {

                                    $query->where(function($query) use($request, $filter) {

                                        $query->where("document_number", "like", $filter)
                                              ->orWhere("name", "like", $filter)
                                              ->orWhere("email", "like", $filter)
                                              ->orWhere("phone_number", "like", $filter);

                                    });

                                }else if(in_array($request->filter_by, ["document_number", "name", "email", "phone_number"])) {

                                    $query->where(function($query) use($request, $filter) {

                                        $query->where($request->filter_by, "like", $filter);

                                    });

                                }

                             })
                             ->where("company_id", $userAuth->company_id)
                             ->orderBy("name", "ASC")
                             ->with(["identityDocumentType"])
                             ->paginate($request->per_page ?? Utilities::$per_page_default);

        return $list;

    }

    public function index() {

        return view("System/general/book_complaints/main");

    }

    public function create() {

        //

    }

    public function store(StoreAssetRequest $request) {

        //

    }

    public function show(Asset $record) {

        //

    }

    public function edit(Asset $record) {

        //

    }

    public function update(UpdateAssetRequest $request, $id) {

        //

    }

    public function destroy(Asset $record) {

        //

    }

}
