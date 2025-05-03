<?php

namespace App\Http\Controllers\Guest;

use App\Helpers\System\Utilities;
use App\Http\Controllers\Controller;
use App\Models\Guest\Company;
use App\Models\System\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, DB};
use stdClass;

class HomeController extends Controller {

    public function initParams(Request $request) {

        $company = $request->get("company");

        $initParams = new stdClass();

        $config = new stdClass();

        $page = $request->page ?? "";

        if(in_array($page, ["main"])) {

            $config->companies = new stdClass();
            $config->companies->statuses = Company::getStatuses();

            $socialsMedia = $company->socialsMedia;

            $company->facebook  = optional($socialsMedia->where("type", "facebook")->first())->link;
            $company->instagram = optional($socialsMedia->where("type", "instagram")->first())->link;
            $company->whatsapp  = optional($socialsMedia->where("type", "whatsapp")->first())->link;
            $company->ownerApp  = Utilities::getOwnerApp();

            $config->company = new stdClass();
            $config->company->records = [$company];

        }

        $initParams->config = $config;
        $initParams->bool   = true;

        return $initParams;

    }

    public function index(Request $request) {

        $company = $request->get("company");

        return view("Guest/general/home/main", ["company" => $company]);

    }

    public function initData(Request $request) {

        $data = [];

        return response()->json(["bool" => true, "msg" => "Data obtenida", "data" => $data], 200);

    }

}
