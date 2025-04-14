<?php

namespace App\Http\Controllers\System;

use App\Helpers\System\Utilities;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, DB};
use stdClass;

use App\Http\Requests\System\TrackingSubscriptions\{CancelTrackingSubscriptionRequest, StoreTrackingSubscriptionRequest, UpdateTrackingSubscriptionRequest};
use App\Models\System\{Subscription};

class TrackingSubscriptionController extends Controller {

    public function initParams(Request $request) {

        $initParams = new stdClass();

        $config = new stdClass();

        $page = $request->page ?? "";

        if(in_array($page, ["main"])) {

            $config->subscriptions = new stdClass();
            $config->subscriptions->statuses = Subscription::getStatuses();

        }

        $initParams->config = $config;
        $initParams->bool   = true;

        return $initParams;

    }

    public function list(Request $request) {

        $userAuth = Auth::user();

        $list = Subscription::select("subscriptions.*")
                            ->when(Utilities::isDefined($request->filter_by), function($query) use($request) {

                                $filter = Utilities::getWordSearch($request->word);

                                if(in_array($request->filter_by, ["customer"])) {


                                    $query->join("customers", "customers.id", "subscriptions.customer_id")
                                          ->where(function($query) use($request, $filter) {

                                                $query->where("document_number", "like", $filter)
                                                      ->orWhere("name", "like", $filter)
                                                      ->orWhere("email", "like", $filter)
                                                      ->orWhere("phone_number", "like", $filter);

                                          });

                                }

                            })
                            ->where("subscriptions.company_id", $userAuth->company_id)
                            ->orderBy("subscriptions.id", "DESC")
                            ->with(["saleHeader", "customer"])
                            ->paginate($request->per_page ?? Utilities::$per_page_default);

        return $list;

    }

    public function index() {

        return view("System/general/tracking_subscriptions/main");

    }

    public function create() {

        //

    }

    public function store(Request $request) { // StoreTrackingSubscriptionRequest

        //

    }

    public function show(Subscription $subscription) {

        //

    }

    public function edit(Subscription $subscription) {

        //

    }

    public function update(Request $request, $id) { // UpdateTrackingSubscriptionRequest

        //

    }

    public function cancel(CancelTrackingSubscriptionRequest $request, $id) {

        $userAuth = Auth::user();

        $subscription = Subscription::findOrFail($id);

        if(Utilities::isDefined($subscription) && in_array($subscription->status, ["active"])) {

            if(Utilities::isDefined($subscription) && $subscription->company_id == $userAuth->company_id) {

                $subscription->motive      = $request->motive ?? "N/A";
                $subscription->status      = "canceled";
                $subscription->updated_at  = now();
                $subscription->updated_by  = $userAuth->id ?? null;
                $subscription->canceled_at = now();
                $subscription->canceled_by = $userAuth->id ?? null;
                $subscription->save();

            }

        }

        $bool = $subscription->wasChanged();
        $msg  = $bool ? "Suscripción anulada correctamente." : "No se ha podido anular la suscripción.";

        return response()->json(["bool" => $bool, "msg" => $msg, "subscription" => $subscription], 200);

    }

    public function destroy(Subscription $subscription) {

        //

    }

}
