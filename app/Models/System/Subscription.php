<?php

namespace App\Models\System;

use App\Helpers\System\Utilities;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model {

    protected $table               = "subscriptions";
    protected $primaryKey          = "id";
    public $incrementing           = true;
    public $timestamps             = true;
    public static $snakeAttributes = true;

    protected $appends = [
        "formatted_type",
        "formatted_status"
    ];

    protected $fillable = [
        "company_id",
        "sale_header_id",
        "sale_body_id",
        "customer_id",
        "start_date",
        "end_date",
        "observation",
        "type",
        "status",
        "created_at",
        "created_by",
        "updated_at",
        "updated_by"
    ];

    // Appends
    public function getFormattedTypeAttribute() {

        return self::getTypes("first", $this->attributes["type"])["label"] ?? "";

    }

    public function getFormattedStatusAttribute() {

        return self::getStatusses("first", $this->attributes["status"])["label"] ?? "";

    }

    // Functions
    public static function getTypes($type = "all", $code = "") {

        $types = [
            ["code" => "sale", "label" => "Venta"],
            ["code" => "manual", "label" => "Manual"]
        ];

        return Utilities::getValues($types, $type, $code);

    }

    public static function getStatusses($type = "all", $code = "") {

        $statusses = [
            ["code" => "active", "label" => "Activo"],
            ["code" => "inactive", "label" => "Inactivo"],
            ["code" => "cancelled", "label" => "Anulado"]
        ];

        return Utilities::getValues($statusses, $type, $code);

    }

    // Relationships
    public function company() {

        return $this->belongsTo(Company::class, "company_id", "id");

    }

    public function saleHeader() {

        return $this->belongsTo(SaleHeader::class, "sale_header_id", "id");

    }

    public function saleBody() {

        return $this->belongsTo(SaleBody::class, "sale_body_id", "id");

    }

    public function customer() {

        return $this->belongsTo(Customer::class, "customer_id", "id");

    }

}
