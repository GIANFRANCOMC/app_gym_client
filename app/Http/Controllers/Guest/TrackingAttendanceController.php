<?php

namespace App\Http\Controllers\Guest;

use App\Helpers\System\Utilities;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, DB};
use stdClass;

// use App\Http\Requests\Guest\TrackingAttendances\{CancelTrackingAttendanceRequest};
use App\Models\Guest\{Attendance, Branch, Customer, Subscription};
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;

class TrackingAttendanceController extends Controller {

    public function initParams(Request $request) {

        $initParams = new stdClass();

        $initParams->bool = true;

        return $initParams;

    }

    public function list(Request $request) {

        //

    }

    public function index(Request $request) {

        $company = $request->get("company");

        $branchId = base64_decode($request->branch ?? "");

        $branch = Branch::where("id", $branchId)
                        ->where("company_id", $company->id)
                        ->first();

        if(Utilities::isDefined($branch)) {

            return view("Guest/general/tracking_attendances/main", ["company" => $company, "branch" => $branch, "withMenu" => false]);

        }else {

            return response()->view("errors.500", ["msg" => "La sucursal no es válida"], 500);

        }

    }

    public function create() {

        //

    }

    public function store(Request $request) { // StoreTrackingAttendanceRequest

        //

    }

    public function show(Attendance $attendance) {

        //

    }

    public function edit(Attendance $attendance) {

        //

    }

    public function update(Request $request, $id) { // UpdateTrackingAttendanceRequest

        //

    }

    public function cancel(CancelTrackingAttendanceRequest $request, $id) {

        //

    }

    public function destroy(Attendance $attendance) {

        //

    }

    public function qrcodeStore(Request $request) { // StoreTrackingAttendanceRequest

        $company = $request->get("company");

        $startDate = now();

        if(!Utilities::isDefined($startDate) || !Utilities::isValidDateFormat($startDate->format("Y-m-d H:i:s"), "Y-m-d H:i:s")) {

            return response()->json(["bool" => false, "msg" => "No se ha podido crear la asistencia, debe de diligenciar el ingreso."], 200);

        }

        $attendances = collect();

        foreach($request->customers as $customerRequest) {

            $customer = Customer::where("id", $customerRequest["customer_id"])
                                ->where("company_id", $company->id)
                                ->first();

            if(!Utilities::isDefined($customer)) {

                $attendances->push(["bool" => false, "msg" => "El cliente seleccionado no es correcto."]);
                continue;

            }

            $subscriptions = Subscription::where("company_id", $company->id)
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

            $attendancesFiltered = Attendance::where("company_id", $company->id)
                                             ->where("branch_id", $request->branch_id)
                                             ->where("customer_id", $customer->id)
                                             ->whereDate("start_date", $startDate->format("Y-m-d"))
                                             ->whereIn("status", ["active", "finalized"])
                                             ->get();

            $activeAttendances = $attendancesFiltered->where("status", "active");

            if(count($activeAttendances) > 0) {

                $attendances->push(["bool" => false, "msg" => "$customer->name: Cuenta con un registro de asistencia 'En curso'."]);
                continue;

            }

            $finalizedAttendances = $attendancesFiltered->whereIn("status", "finalized");

            if(count($finalizedAttendances) >= $attendanceLimitPerDay) {

                $attendances->push(["bool" => false, "msg" => "$customer->name: Límite de ingresos excedido ($attendanceLimitPerDay por día)."]);
                continue;

            }

            $attendance = new Attendance();
            $attendance->company_id  = $company->id;
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
