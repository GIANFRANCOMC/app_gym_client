<?php

namespace App\Models\System;

use App\Helpers\System\Utilities;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Customer extends Model {

    protected $table               = "customers";
    protected $primaryKey          = "id";
    public $incrementing           = true;
    public $timestamps             = true;
    public static $snakeAttributes = true;

    protected $appends = [
        "formatted_status"
    ];

    protected $fillable = [
        "company_id",
        "identity_document_type_id",
        "document_number",
        "name",
        "email",
        "phone_number",
        "status",
        "created_at",
        "created_by",
        "updated_at",
        "updated_by"
    ];

    // Appends
    public function getFormattedStatusAttribute() {

        return self::getStatuses("first", $this->attributes["status"])["label"] ?? "";

    }

    // Functions
    public static function getStatuses($type = "all", $code = "") {

        $statuses = [
            ["code" => "active", "label" => "Activo"],
            ["code" => "inactive", "label" => "Inactivo"]
        ];

        return Utilities::getValues($statuses, $type, $code);

    }

    public static function getAll($type = "default") {

        $userAuth = Auth::user();

        return Customer::where("company_id", $userAuth->company_id)
                       ->when(in_array($type, ["sale"]), function($query) {

                            $query->whereIn("status", ["active"]);

                       })
                       ->when(in_array($type, ["tracking_subscription"]), function($query) {

                            // $query->whereIn("status", ["active"]);

                       })
                       ->when(in_array($type, ["tracking_attendance"]), function($query) {

                            // $query->whereIn("status", ["active"]);

                       })
                       ->get();

    }

    // Relationships
    public function company() {

        return $this->belongsTo(Company::class, "company_id", "id");

    }

    public function identityDocumentType() {

        return $this->belongsTo(IdentityDocumentType::class, "identity_document_type_id", "id");

    }

    public function attendances() {

        return $this->hasMany(Attendance::class, "customer_id", "id")
                    ->whereIn("status", ["active"]);

    }

    public function salesHeader() {

        return $this->hasMany(SaleHeader::class, "holder_id", "id")
                    ->whereIn("status", ["active"]);

    }

}
