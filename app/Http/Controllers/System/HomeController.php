<?php

namespace App\Http\Controllers\System;

use App\Helpers\System\Utilities;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, DB};
use stdClass;

use App\Models\System\{UserPreference};

class HomeController extends Controller {

    public function initParams(Request $request) {

        $initParams = new stdClass();

        $config = new stdClass();

        $page = $request->page ?? "";

        if(in_array($page, ["main"])) {

            //

        }

        $initParams->config = $config;
        $initParams->bool   = true;

        return $initParams;

    }

    public function index() {

        return view("System/general/home/main");

    }

    public function update(Request $request, $id) {

        $userAuth = Auth::user();

        $data = [
            "records" => [
                [
                    "sub_section_id" => $id,
                    "is_favorite" => $request["is_favorite"]
                ]
            ]
        ];

        $updateItems = UserPreference::updateItems($userAuth->id, "config_companies_sub_sections", $data);

        return response()->json(["bool" => $updateItems["bool"], "msg" => "Cambio realizado.", "preferences" => $userAuth->formatted_preferences], 200);

    }

}
