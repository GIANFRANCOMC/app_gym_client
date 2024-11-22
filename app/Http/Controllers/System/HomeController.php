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

        $initParams->config = $config;
        $initParams->bool   = true;

        return $initParams;

    }

    public function index() {

        return view("System/general/home/main");

    }

    public function initData(Request $request) {

        $sales = SaleHeader::whereDate("created_at", date("Y-m-d"))
                           ->orderBy("created_at", "DESC")
                           ->with(["serie.documentType", "holder", "currency"])
                           ->get();

        $cancelledSales = $sales->whereIn("status", ["cancelled"])
                                ->values();

        $branches = Branch::whereIn("status", ["active"])
                          ->get();

        $users = User::whereIn("status", ["active"])
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
                    "count" => $branches->count()
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
