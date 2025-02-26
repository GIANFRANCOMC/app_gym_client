<?php

namespace App\Http\Controllers\System;

use App\Helpers\System\Utilities;
use App\Http\Controllers\Controller;
use App\Models\System\Branch;
use App\Models\System\SaleHeader;
use App\Models\System\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, DB};
use stdClass;

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

    public function initData(Request $request) {

        $userAuth = Auth::user();

        $branches = Branch::where("company_id", $userAuth->company_id)
                          ->with(["series"])
                          ->get();

        $serieIds = $branches->pluck("series.*.id")->flatten();

        $sales = SaleHeader::whereDate("created_at", date("Y-m-d"))
                           ->whereIn("serie_id", $serieIds)
                           ->orderBy("created_at", "DESC")
                           ->with(["serie.documentType", "holder", "currency"])
                           ->get();

        $cancelledSales = $sales->whereIn("status", ["cancelled"])
                                ->values();

        $users = User::where("company_id", $userAuth->company_id)
                      ->whereIn("status", ["active"])
                      ->get();

        $data = [
            "sales" => [
                "all" => [
                    "total"  => $sales->sum("total"),
                    "count"  => $sales->count(),
                    "latest" => $sales->take(10)
                ],
                "cancelled" => [
                    "total" => $cancelledSales->sum("total"),
                    "count" => $cancelledSales->count()
                ]
            ],
            "branches" => [
                "valid" => [
                    "count" => $branches->whereIn("status", ["active"])->count()
                ]
            ],
            "users" => [
                "valid" => [
                    "count" => $users->count()
                ]
            ]
        ];

        return response()->json(["bool" => true, "msg" => "Data obtenida", "data" => $data], 200);

    }

}
