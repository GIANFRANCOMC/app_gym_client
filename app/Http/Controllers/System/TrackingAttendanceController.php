<?php

namespace App\Http\Controllers\System;

use App\Helpers\System\Utilities;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, DB};
use stdClass;

use App\Http\Requests\System\TrackingAttendances\{CancelTrackingAttendanceRequest};
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

        $list = Attendance::when(Utilities::isDefined($request->customer_id), function($query) use($request) {

                            $query->where(function($query) use($request) {

                                $query->where("customer_id", $request->customer_id);

                            });

                          })
                          ->when(Utilities::isDefined($request->status), function($query) use($request) {

                            $query->where(function($query) use($request) {

                                $query->where("status", $request->status);

                            });

                          })
                          ->where("company_id", $userAuth->company_id)
                          ->where("branch_id", $branch->id)
                          ->whereDate("created_at", $request->start_date ?? date("Y-m-d"))
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

    public function store(Request $request) { // StoreTrackingAttendanceRequest

        $userAuth = Auth::user();

        $startDate = str_replace("T", " ", $request->start_date);

        if(!Utilities::isDefined($startDate) || !Utilities::isValidDateFormat($startDate, "Y-m-d H:i")) {

            return response()->json(["bool" => false, "msg" => "No se ha podido crear la asistencia, debe de diligenciar el ingreso."], 200);

        }

        $attendance = null;

        $customer = Customer::where("id", $request->customer_id)
                            ->where("company_id", $userAuth->company_id)
                            ->first();

        if(!Utilities::isDefined($customer)) {

            return response()->json(["bool" => false, "msg" => "El cliente seleccionado no es correcto."], 200);

        }

        $subscriptions = Subscription::where("company_id", $userAuth->company_id)
                                     ->where("branch_id", $request->branch_id)
                                     ->where("customer_id", $customer->id)
                                     ->where("start_date", "<=", $startDate)
                                     ->where("end_date", ">=", $startDate)
                                     ->where("status", "active")
                                     ->orderBy("attendance_limit_per_day", "DESC")
                                     ->get();

        if(count($subscriptions) === 0) {

            return response()->json(["bool" => false, "msg" => "$customer->name: No cuenta con una membresía vigente en la sucursal."], 200);

        }

        $subscriptionsFirst = $subscriptions->first();
        $attendanceLimitPerDay = intval($subscriptionsFirst->attendance_limit_per_day);

        $attendancesFiltered = Attendance::where("company_id", $userAuth->company_id)
                                         ->where("branch_id", $request->branch_id)
                                         ->where("customer_id", $customer->id)
                                         ->whereDate("start_date", Carbon::createFromFormat("Y-m-d H:i", $startDate)->format("Y-m-d"))
                                         ->whereIn("status", ["active", "finalized"])
                                         ->get();

        $activeAttendances = $attendancesFiltered->where("status", "active");

        if(count($activeAttendances) > 0) {

            return response()->json(["bool" => false, "msg" => "$customer->name: Cuenta con un registro de asistencia 'En curso'."], 200);

        }

        $finalizedAttendances = $attendancesFiltered->where("status", "finalized");

        if(count($finalizedAttendances) >= $attendanceLimitPerDay) {

            return response()->json(["bool" => false, "msg" => "$customer->name: Límite excedido."], 200);

        }

        DB::transaction(function() use($request, $userAuth, &$attendance, $customer, $startDate) {

            $attendance = new Attendance();
            $attendance->company_id  = $userAuth->company_id;
            $attendance->branch_id   = $request->branch_id;
            $attendance->customer_id = $customer->id;
            $attendance->start_date  = $startDate;
            $attendance->end_date    = null; // $request->end_date;
            $attendance->observation = $request->observation ?? "";
            $attendance->type        = "manual";
            $attendance->status      = "active";
            $attendance->created_at  = now();
            $attendance->created_by  = $userAuth->id ?? null;
            $attendance->save();

        });

        $bool = Utilities::isDefined($attendance);
        $msg  = $bool ? "$customer->name: Asistencia creada correctamente." : "$customer->name: No se ha podido crear la asistencia.";

        return response()->json(["bool" => $bool, "msg" => $msg, "attendance" => $attendance], 200);

    }

    public function show(Attendance $attendance) {

        //

    }

    public function edit(Attendance $attendance) {

        //

    }

    public function update(Request $request, $id) { // UpdateTrackingAttendanceRequest

        $userAuth = Auth::user();

        if(!Utilities::isDefined($request->end_date)) {

            return response()->json(["bool" => false, "msg" => "No se ha podido finalizar la asistencia, debe de diligenciar la salida."], 200);

        }

        $attendance = Attendance::where("id", $id)
                                ->where("company_id", $userAuth->company_id)
                                ->where("branch_id", $request->branch_id)
                                ->where("customer_id", $request->customer_id)
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
        $msg  = $bool ? "Asistencia finalizada correctamente." : "No se ha podido finalizar la asistencia.";

        return response()->json(["bool" => $bool, "msg" => $msg, "attendance" => $attendance], 200);

    }

    public function cancel(CancelTrackingAttendanceRequest $request, $id) {

        $userAuth = Auth::user();

        $attendance = Attendance::findOrFail($id);

        if(Utilities::isDefined($attendance) && in_array($attendance->status, ["finalized"])) {

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

    public function qrcodeStore(Request $request) { // StoreTrackingAttendanceRequest

        $userAuth = Auth::user();

        $startDate = str_replace("T", " ", $request->start_date);

        if(!Utilities::isDefined($startDate) || !Utilities::isValidDateFormat($startDate, "Y-m-d H:i")) {

            return response()->json(["bool" => false, "msg" => "No se ha podido crear la asistencia, debe de diligenciar el ingreso."], 200);

        }

        $attendances = collect();

        foreach($request->customers as $customerRequest) {

            $customer = Customer::where("id", $customerRequest["customer_id"])
                                ->where("company_id", $userAuth->company_id)
                                ->first();

            if(!Utilities::isDefined($customer)) {

                $attendances->push(["bool" => false, "msg" => "El cliente seleccionado no es correcto."]);
                continue;

            }

            $subscriptions = Subscription::where("company_id", $userAuth->company_id)
                                         ->where("branch_id", $request->branch_id)
                                         ->where("customer_id", $customer->id)
                                         ->where("start_date", "<=", $startDate)
                                         ->where("end_date", ">=", $startDate)
                                         ->where("status", "active")
                                         ->orderBy("attendance_limit_per_day", "DESC")
                                         ->get();

            if(count($subscriptions) === 0) {

                $attendances->push(["bool" => false, "msg" => "$customer->name: No cuenta con una membresía vigente en la sucursal."]);
                continue;

            }

            $subscriptionsFirst = $subscriptions->first();
            $attendanceLimitPerDay = intval($subscriptionsFirst->attendance_limit_per_day);

            $attendancesFiltered = Attendance::where("company_id", $userAuth->company_id)
                                             ->where("branch_id", $request->branch_id)
                                             ->where("customer_id", $customer->id)
                                             ->whereDate("start_date", Carbon::createFromFormat("Y-m-d H:i", $startDate)->format("Y-m-d"))
                                             ->whereIn("status", ["active", "finalized"])
                                             ->first();

            $activeAttendances = $attendancesFiltered->where("status", "active");

            if(count($activeAttendances) > 0) {

                $attendances->push(["bool" => false, "msg" => "$customer->name: Cuenta con un registro de asistencia 'En curso'."], 200);
                continue;

            }

            $finalizedAttendances = $attendancesFiltered->where("status", "finalized");

            if(count($finalizedAttendances) >= $attendanceLimitPerDay) {

                $attendances->push(["bool" => false, "msg" => "$customer->name: Límite excedido."], 200);
                continue;

            }

            $attendance = new Attendance();
            $attendance->company_id  = $userAuth->company_id;
            $attendance->branch_id   = $request->branch_id;
            $attendance->customer_id = $customer->id;
            $attendance->start_date  = $startDate;
            $attendance->end_date    = null; // $request->end_date;
            $attendance->observation = $request->observation ?? "";
            $attendance->type        = "qr";
            $attendance->status      = "active";
            $attendance->created_at  = now();
            $attendance->created_by  = $userAuth->id ?? null;
            $attendance->save();

            $attendances->push(["bool" => true, "msg" => "$customer->name: Asistencia creada correctamente."]);

        }

        $bool = count($attendances->where("bool", true)) > 0;
        $msg  = $bool ? "Asistencias creadas correctamente." : "No se han podido crear las asistencias.";

        return response()->json(["bool" => $bool, "msg" => $msg, "attendances" => $attendances], 200);

    }

}
