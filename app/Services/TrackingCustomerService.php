<?php

namespace App\Services;

use App\Helpers\System\Utilities;
use App\Models\System\Attendance;
use App\Models\System\Customer;
use App\Models\System\SaleHeader;
use App\Models\System\Subscription;

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

    public function get(array $data) {

        $response = [
            "bool" => false,
            "msg" => ""
        ];

        $companyId   = $data["company_id"];
        $customerId  = $data["customer_id"] ?? "";
        $customerDocumentNumber = $data["customer_document_number"] ?? "";
        $customerAttendanceType = "";

        $types = $data["types"] ?? [];

        if(in_array($customerAttendanceType, ["document_number"])) {

            $customer = $this->getValidCustomer($customerDocumentNumber, $companyId, $customerAttendanceType);

        }else {

            $customer = $this->getValidCustomer($customerId, $companyId, $customerAttendanceType);

        }

        if(!Utilities::isDefined($customer)) {

            $response["msg"] = "No se ha encontrado el cliente solicitado.";

            return $response;

        }

        $sales = SaleHeader::where("holder_id", $customer->id)
                           ->with(["serie.documentType", "currency"])
                           ->get();

        $subscriptions = Subscription::where("customer_id", $customer->id)
                                     ->with(["branch"])
                                     ->get();

        $attendances = Attendance::where("customer_id", $customer->id)
                                 ->with(["branch"])
                                 ->get();

        $response["tracking"] = [
            "customer" => $customer,
            "sales" => $sales,
            "subscriptions" => $subscriptions,
            "attendances" => $attendances
        ];

        $response["msg"] = "Información encontrada";

        return $response;

    }

}
