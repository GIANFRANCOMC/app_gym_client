<?php

namespace App\Http\Controllers\System;

use App\Helpers\System\Utilities;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, DB};
use stdClass;

// use App\Http\Requests\System\TrackingSubscriptions\{CancelTrackingSubscriptionRequest, StoreTrackingSubscriptionRequest, UpdateTrackingSubscriptionRequest};
use App\Models\System\{Branch, Customer, SubscriptionEmail, Subscription};
use Illuminate\Pagination\LengthAwarePaginator;

class TrackingNotificationController extends Controller {

    public function initParams(Request $request) {

        $initParams = new stdClass();

        $config = new stdClass();

        $page = $request->page ?? "";

        if(in_array($page, ["main"])) {

            $config->branches = new stdClass();
            $config->branches->records = Branch::getAll("tracking_subscription");

            $config->customers = new stdClass();
            $config->customers->records = Customer::getAll("tracking_subscription");

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

        $list = Subscription::when(Utilities::isDefined($request->customer_id), function($query) use($request) {

                                $query->where(function($query) use($request) {

                                    $query->where("customer_id", $request->customer_id);

                                });

                            })
                            ->when(Utilities::isDefined($request->start_date), function($query) use($request) {

                                $query->where(function($query) use($request) {

                                    $query->where("start_date", ">=", $request->start_date." 00:00:00");

                                });

                            })
                            ->when(Utilities::isDefined($request->end_date), function($query) use($request) {

                                $query->where(function($query) use($request) {

                                    $query->where("end_date", "<=", $request->end_date." 23:59:59");

                                });

                            })
                            ->when(Utilities::isDefined($request->status), function($query) use($request) {

                                $query->where(function($query) use($request) {

                                    $query->where("status", $request->status);

                                });

                            })
                            ->where("company_id", $userAuth->company_id)
                            ->where("branch_id", $branch->id)
                            ->orderBy("id", "DESC")
                            ->with(["branch", "saleHeader", "customer"])
                            ->paginate($request->per_page ?? Utilities::$per_page_default);

        return $list;

    }

    public function index() {

        return view("System/general/tracking_notifications/main");

    }

    public function create() {

        //

    }

    public function store(Request $request) { // StoreTrackingSubscriptionRequest

        //

    }

    public function show(SubscriptionEmail $email) {

        //

    }

    public function edit(SubscriptionEmail $email) {

        //

    }

    public function update(Request $request, $id) { // UpdateTrackingSubscriptionRequest

        //

    }

    public function destroy(SubscriptionEmail $email) {

        //

    }

}
