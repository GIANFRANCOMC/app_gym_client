<?php

namespace App\Services;

use App\Helpers\System\Utilities;
use App\Models\System\{Attendance, Customer, SaleHeader, Subscription};
use Carbon\Carbon;

class TrackingCustomerService {

    public function getValidCustomer($code, $companyId, $type = ""): ?Customer {

        if(in_array($type, ["document_number"])) {

            return Customer::where("document_number", $code)
                           ->where("company_id", $companyId)
                           ->with(["identityDocumentType"])
                           ->first();

        }else {

            return Customer::where("id", $code)
                           ->where("company_id", $companyId)
                           ->with(["identityDocumentType"])
                           ->first();

        }

    }

    public function getDateRangeFromCode(string $code): array {

        $now = Carbon::now();

        if($code === "this_year") {

            return [
                "from" => $now->copy()->startOfYear()->startOfDay(),
                "to"   => $now->copy()->endOfDay()
            ];

        }

        if(preg_match("/^last_(\d+)_([a-z]+)$/", $code, $matches)) {

            $amount = (int) $matches[1];
            $unit = $matches[2]; // "days", "months", "years"

            return [
                "from" => $now->copy()->sub($unit, $amount)->startOfDay(),
                "to"   => $now->copy()->endOfDay()
            ];

        }

        return [
            "from" => $now->copy()->subDays(30)->startOfDay(),
            "to"   => $now->copy()->endOfDay()
        ];

    }

    public function getInformation($customer, $range, $type) {

        $records = [];

        if(in_array($type, ["sales"])) {

            $records = SaleHeader::where("holder_id", $customer->id)
                                 ->whereBetween("created_at", [$range["from"], $range["to"]])
                                 ->with(["serie.documentType", "serie.branch", "currency"])
                                 ->get();

        }else if(in_array($type, ["subscriptions"])) {

            $records = Subscription::where("customer_id", $customer->id)
                                   ->whereBetween("created_at", [$range["from"], $range["to"]])
                                   ->with(["branch"])
                                   ->get();

        }else if(in_array($type, ["attendances"])) {

            $records = Attendance::where("customer_id", $customer->id)
                                 ->whereBetween("created_at", [$range["from"], $range["to"]])
                                 ->with(["branch"])
                                 ->get();

        }

        return $records;

    }

    public function get(array $data) {

        $response = [
            "bool" => false,
            "msg" => ""
        ];

        $companyId   = $data["company_id"];
        $customerId  = $data["customer_id"] ?? "";
        $customerDocumentNumber = $data["customer_document_number"] ?? "";
        $customerAttendanceType = "";
        $periodType  = $data["period_type"] ?? "last_3_months";
        $options = $data["options"] ?? [];

        if(in_array($customerAttendanceType, ["document_number"])) {

            $customer = $this->getValidCustomer($customerDocumentNumber, $companyId, $customerAttendanceType);

        }else {

            $customer = $this->getValidCustomer($customerId, $companyId, $customerAttendanceType);

        }

        if(!Utilities::isDefined($customer)) {

            $response["msg"] = "No se ha encontrado el cliente solicitado.";

            return $response;

        }

        $range = $this->getDateRangeFromCode($periodType);

        $response["tracking"] = [
            "customer" => $customer,
            "extras" => [
                "period_type" => $periodType,
                "options" => $options
            ]
        ];

        $information = $options["information"] ?? [];

        foreach($information as $opt) {

            $response["tracking"][$opt] = $this->getInformation($customer, $range, $opt);

        }

        $response["bool"] = true;
        $response["msg"]  = "Información encontrada";

        return $response;

    }

}
