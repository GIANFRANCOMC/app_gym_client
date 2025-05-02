<?php

namespace App\Http\Controllers\Guest;

use App\Helpers\System\Utilities;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{DB};
use stdClass;

use App\Http\Requests\System\Assets\{StoreAssetRequest, UpdateAssetRequest};
use App\Models\Guest\{BookComplaint, IdentityDocumentType};

class BookComplaintController extends Controller {

    public function initParams(Request $request) {

        $initParams = new stdClass();

        $config = new stdClass();

        $page = $request->page ?? "";

        if(in_array($page, ["main"])) {

            $config->bookComplaints = new stdClass();
            $config->bookComplaints->types    = BookComplaint::getTypes();
            $config->bookComplaints->statuses = BookComplaint::getStatuses();

            $config->identityDocumentTypes = new stdClass();
            $config->identityDocumentTypes->records = IdentityDocumentType::whereIn("id", [1, 2, 4])
                                                                          ->get();

        }

        $initParams->config = $config;
        $initParams->bool   = true;

        return $initParams;

    }

    public function list(Request $request) {

        //

    }

    public function index(Request $request) {

        $company = $request->get("company");

        return view("Guest/general/book_complaints/main", ["company" => $company]);

    }

    public function create() {

        //

    }

    public function store(Request $request) { // public function store(StoreAssetRequest $request) {

        $bookComplaint = null;

        $company = $request->get("company");

        DB::transaction(function() use($request, $company, &$bookComplaint) {

            $bookComplaint = new BookComplaint();
            $bookComplaint->company_id                = $company->id;
            $bookComplaint->identity_document_type_id = $request->input("identity_document_type_id", "");
            $bookComplaint->document_number           = $request->input("document_number ", "");
            $bookComplaint->name                      = $request->input("name", "");
            $bookComplaint->email                     = $request->input("email", "");
            $bookComplaint->phone_number              = $request->input("phone_number", "");
            $bookComplaint->type                      = $request->input("type", "");
            $bookComplaint->description               = $request->input("description", "");
            $bookComplaint->request                   = $request->input("request", "");
            $bookComplaint->evidence                  = $request->input("evidence", "");
            $bookComplaint->admin_response            = "";
            $bookComplaint->status                    = "pending";
            $bookComplaint->created_at                = now();
            $bookComplaint->created_by                = null;
            $bookComplaint->save();

        });

        $bool = Utilities::isDefined($bookComplaint);
        $msg  = $bool ? "Registro creado correctamente." : "No se ha podido crear el registro.";

        return response()->json(["bool" => $bool, "msg" => $msg, "bookComplaint" => $bookComplaint], 200);

    }

    public function show(BookComplaint $record) {

        //

    }

    public function edit(BookComplaint $record) {

        //

    }

    public function update(UpdateAssetRequest $request, $id) {

        //

    }

    public function destroy(BookComplaint $record) {

        //

    }

}
