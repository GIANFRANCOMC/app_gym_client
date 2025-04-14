<?php

namespace App\Http\Controllers\System;

use App\Helpers\System\Utilities;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, DB};
use stdClass;

use App\Http\Requests\System\TrackingSubscriptions\{CancelTrackingSubscriptionRequest, StoreTrackingSubscriptionRequest, UpdateTrackingSubscriptionRequest};
use App\Models\System\{Attendance, Branch, Customer, Subscription};
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;

class TrackingAttendanceController extends Controller {

    public function initParams(Request $request) {

        $initParams = new stdClass();

        $config = new stdClass();

        $page = $request->page ?? "";

        if(in_array($page, ["main"])) {

            $config->branches = new stdClass();
            $config->branches->records = Branch::getAll("tracking_attendance");

            $config->customers = new stdClass();
            $config->customers->records = Customer::getAll("tracking_attendance");

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

        $list = Attendance::where("company_id", $userAuth->company_id)
                          ->where("branch_id", $branch->id)
                          ->orderBy("id", "DESC")
                          ->with(["branch", "customer"])
                          ->paginate($request->per_page ?? Utilities::$per_page_max);

        return $list;

    }

    public function index() {

        return view("System/general/tracking_attendances/main");

    }

    public function create() {

        //

    }

    public function store(Request $request) { // StoreTrackingSubscriptionRequest

        $userAuth = Auth::user();

        $attendance = null;

        $subscriptions = Subscription::where("company_id", $userAuth->company_id)
                                     ->where("branch_id", $request->branch_id)
                                     ->where("customer_id", $request->customer_id)
                                     ->where("start_date", "<=", $request->start_date)
                                     ->where("end_date", ">=", $request->start_date)
                                     ->where("status", "!=", "canceled")
                                     ->get();

        if(count($subscriptions) === 0) {

            return response()->json(["bool" => false, "msg" => "El cliente seleccionado no cuenta con una suscripción vigente."], 200);

        }

        $attendanceActive = Attendance::where("company_id", $userAuth->company_id)
                                      ->where("branch_id", $request->branch_id)
                                      ->where("customer_id", $request->customer_id)
                                      ->where("status", "active")
                                      ->first();

        if(Utilities::isDefined($attendanceActive)) {

            return response()->json(["bool" => false, "msg" => "El cliente seleccionado cuenta con un registro de asistencia activo."], 200);

        }

        DB::transaction(function() use($request, $userAuth, &$attendance) {

            $attendance = new Attendance();
            $attendance->company_id  = $userAuth->company_id;
            $attendance->branch_id   = $request->branch_id;
            $attendance->customer_id = $request->customer_id;
            $attendance->start_date  = $request->start_date;
            $attendance->end_date    = $request->end_date;
            $attendance->observation = $request->observation ?? "";
            $attendance->type        = "manual";
            $attendance->status      = "active";
            $attendance->created_at  = now();
            $attendance->created_by  = $userAuth->id ?? null;
            $attendance->save();

        });

        $bool = Utilities::isDefined($attendance);
        $msg  = $bool ? "Asistencia creada correctamente." : "No se ha podido crear la asistencia.";

        return response()->json(["bool" => $bool, "msg" => $msg, "attendance" => $attendance], 200);

    }

    public function show(Attendance $attendance) {

        //

    }

    public function edit(Attendance $attendance) {

        //

    }

    public function update(Request $request, $id) { // UpdateTrackingSubscriptionRequest

        $userAuth = Auth::user();

        $attendance = Attendance::where("id", $id)
                                ->where("company_id", $userAuth->company_id)
                                ->where("status", "active")
                                ->first();

        if(Utilities::isDefined($attendance)) {

            DB::transaction(function() use($request, $userAuth, &$attendance) {

                $attendance->end_date   = $request->end_date;
                $attendance->status     = "finalized";
                $attendance->updated_at = now();
                $attendance->updated_by = $userAuth->id ?? null;
                $attendance->save();

            });

        }

        $bool = Utilities::isDefined($attendance);
        $msg  = $bool ? "Asistencia editado correctamente." : "No se ha podido editar la asistencia.";

        return response()->json(["bool" => $bool, "msg" => $msg, "attendance" => $attendance], 200);

    }

    public function cancel(CancelTrackingSubscriptionRequest $request, $id) {

        $userAuth = Auth::user();

        $attendance = Attendance::findOrFail($id);

        if(Utilities::isDefined($attendance) && in_array($attendance->status, ["active"])) {

            if(Utilities::isDefined($attendance) && $attendance->company_id == $userAuth->company_id) {

                $attendance->motive      = $request->motive ?? "N/A";
                $attendance->status      = "canceled";
                $attendance->updated_at  = now();
                $attendance->updated_by  = $userAuth->id ?? null;
                $attendance->canceled_at = now();
                $attendance->canceled_by = $userAuth->id ?? null;
                $attendance->save();

            }

        }

        $bool = $attendance->wasChanged();
        $msg  = $bool ? "Asistencia anulada correctamente." : "No se ha podido anular la asistencia.";

        return response()->json(["bool" => $bool, "msg" => $msg, "attendance" => $attendance], 200);

    }

    public function destroy(Attendance $attendance) {

        //

    }

}
