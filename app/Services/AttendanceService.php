<?php

namespace App\Services;

use App\Helpers\System\Utilities;

use App\Models\System\{Attendance, Customer, Subscription};
use Carbon\Carbon;

class AttendanceService {

    public function validateStartDate(?Carbon $startDate): bool {

        return Utilities::isDefined($startDate) &&
               Utilities::isValidDateFormat($startDate->format("Y-m-d H:i:s"), "Y-m-d H:i:s");

    }

    public function getValidCustomer($customerId, $companyId): ?Customer {

        return Customer::where("id", $customerId)
                       ->where("company_id", $companyId)
                       ->first();

    }

    public function getValidSubscriptions($companyId, $branchId, $customerId, $startDate) {

        return Subscription::where("company_id", $companyId)
                           ->where("branch_id", $branchId)
                           ->where("customer_id", $customerId)
                           ->where("start_date", "<=", $startDate)
                           ->where("end_date", ">=", $startDate)
                           ->where("status", "active")
                           ->orderBy("attendance_limit_per_day", "DESC")
                           ->get();

    }

    public function checkAttendanceLimits($companyId, $branchId, $customerId, $startDate, $limit) {

        $attendances = Attendance::where("company_id", $companyId)
                                 ->where("branch_id", $branchId)
                                 ->where("customer_id", $customerId)
                                 ->whereDate("start_date", $startDate->format("Y-m-d"))
                                 ->whereIn("status", ["active", "finalized"])
                                 ->get();

        $active = $attendances->where("status", "active")->count();
        $finalized = $attendances->where("status", "finalized")->count();

        return [
            "hasActive" => $active > 0,
            "exceedsLimit" => $finalized >= $limit
        ];

    }

    public function createAttendance($data): Attendance {

        return Attendance::create([
            "company_id"  => $data["company_id"],
            "branch_id"   => $data["branch_id"],
            "customer_id" => $data["customer_id"],
            "start_date"  => $data["start_date"],
            "end_date"    => $data["end_date"],
            "observation" => $data["observation"],
            "type"        => $data["type"],
            "status"      => "active",
            "created_at"  => now(),
            "created_by"  => $data["user_id"]
        ]);

    }

    // Flow
    public function validateAndCreateAttendance(array $data): array {

        $response = [
            "bool" => false,
            "msg" => ""
        ];

        $companyId   = $data["company_id"];
        $branchId    = $data["branch_id"];
        $customerId  = $data["customer_id"];
        $startDate   = $data["start_date"] ?? null; // Carbon
        $endDate     = $data["end_date"] ?? null;   // Carbon
        $observation = $data["observation"] ?? "";
        $userId      = $data["user_id"] ?? null;
        $type        = $data["type"] ?? "form_manual";
        $action      = $data["action"] ?? "automatic";

        $customer = $this->getValidCustomer($customerId, $companyId);

        if(!Utilities::isDefined($customer)) {

            $response["msg"] = "No se ha encontrado el cliente solicitado.";

            return $response;

        }

        $response["customer"] = $customer;

        $activeAttendance = Attendance::where("company_id", $companyId)
                                      ->where("branch_id", $branchId)
                                      ->where("customer_id", $customerId)
                                      ->where("status", "active")
                                      ->latest("start_date")
                                      ->first();

        if(in_array($action, ["checkout"]) && !Utilities::isDefined($activeAttendance)) {

            $response["msg"] = "$customer->name: No se ha podido concluir la asistencia.";

            return $response;

        }

        if(Utilities::isDefined($activeAttendance)) {

            $proposedStartDate = Carbon::parse($activeAttendance->start_date);
            $proposedEndDate   = $endDate;

            if(!$proposedEndDate->greaterThan($proposedStartDate)) {

                $response["msg"] = "$customer->name: La salida debe ser mayor al ingreso ".$proposedStartDate->format("d-m-Y h:i A").".";

                return $response;

            }

            if($proposedEndDate->diffInMinutes($proposedStartDate) < 2) {

                $response["msg"] = "$customer->name: La salida debe ser al menos 2 minutos después del ingreso ".$proposedStartDate->format("d-m-Y h:i A").".";

                return $response;

            }

            $activeAttendance->end_date   = $proposedEndDate;
            $activeAttendance->status     = "finalized";
            $activeAttendance->updated_at = now();
            $activeAttendance->updated_by = $userId;
            $activeAttendance->save();

            $response["bool"]   = true;
            $response["msg"]    = "¡Hasta pronto, $customer->name! Gracias por visitarnos.";
            $response["action"] = "checkout";

            return $response;

        }

        // Break: Checkout
        if(in_array($action, ["checkout"])) {

            $response["msg"] = "$customer->name: Sin respuesta.";

            return $response;

        }

        $subscriptions = $this->getValidSubscriptions($companyId, $branchId, $customer->id, $startDate);

        if($subscriptions->isEmpty()) {

            $response["msg"] = "$customer->name: No cuenta con una membresía vigente en la sucursal.";

            return $response;

        }

        $subscription = $subscriptions->first();
        $limitPerDay  = intval($subscription->attendance_limit_per_day);

        $check = $this->checkAttendanceLimits($companyId, $branchId, $customer->id, $startDate, $limitPerDay);

        if($check["hasActive"]) {

            $response["msg"] = "$customer->name: Cuenta con un registro de asistencia 'En curso'.";

            return $response;

        }

        /* if($check["exceedsLimit"]) {

            $response["msg"] = "$customer->name: Límite de ingresos excedido ($limitPerDay por día).";

            return $response;

        } */

        $result = $this->createAttendance([
            "company_id"  => $companyId,
            "branch_id"   => $branchId,
            "customer_id" => $customer->id,
            "start_date"  => $startDate,
            "end_date"    => null, // $endDate,
            "observation" => $observation,
            "user_id"     => $userId,
            "type"        => $type
        ]);

        $response["bool"]   = true;
        $response["msg"]    = "¡Bienvenido, $customer->name! Disfruta tu rutina.";
        $response["action"] = "checkin";

        return $response;

    }

}
