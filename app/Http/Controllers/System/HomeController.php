<?php

namespace App\Http\Controllers\System;

use App\Helpers\System\Utilities;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, DB};
use stdClass;

use App\Models\System\{CompanySubSection};
use App\Services\CompanySectionService;

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

        $companiesSubSections = CompanySubSection::where("sub_section_id", $id)
                                                 ->where("company_id", $userAuth->company_id)
                                                 ->where("status", "active")
                                                 ->with("subSection")
                                                 ->get();

        $isFavorite = $request["is_favorite"] ?? false;

        $sectionName = "";

        foreach($companiesSubSections as $companySubSection) {

            $sectionName = $companySubSection->subSection->dom_label;

            $companySubSection->is_favorite = !($isFavorite == 1);
            $companySubSection->updated_at  = now();
            $companySubSection->updated_by  = $userAuth->id;
            $companySubSection->save();

        }

        $sections = CompanySectionService::getSections($userAuth->company_id, true);

        return response()->json(["bool" => true, "msg" => "$sectionName: Cambio realizado.", "sections" => $sections], 200);

    }

}
