<?php

namespace App\Models\System;

use App\Helpers\System\Utilities;
use Exception;
use Illuminate\Database\Eloquent\Model;

class SaleBody extends Model {

    protected $table               = "sales_body";
    protected $primaryKey          = "id";
    public $incrementing           = true;
    public $timestamps             = true;
    public static $snakeAttributes = true;

    protected $appends = [
        "formatted_type",
        "formatted_extras",
        "formatted_status"
    ];

    protected $fillable = [
        "sale_header_id",
        "item_id",
        "currency_id",
        "name",
        "quantity",
        "price",
        "total",
        "customer_id",
        "type",
        "observation",
        "extras",
        "status",
        "created_at",
        "created_by",
        "updated_at",
        "updated_by",
        "canceled_at",
        "canceled_by"
    ];

    // Appends
    public function getFormattedTypeAttribute() {

        return self::getTypes("first", $this->attributes["type"])["label"] ?? "";

    }

    public function getFormattedExtrasAttribute() {

        try {

            return json_decode($this->extras);

        }catch(Exception $e) {

            return "";

        }

    }

    public function getFormattedStatusAttribute() {

        return self::getStatuses("first", $this->attributes["status"])["label"] ?? "";

    }

    // Functions
    public static function getDurationTypes($type = "all", $code = "") {

        $types = [
            ["code" => "hour", "label" => "Hora", "plural" => "Horas"],
            ["code" => "day", "label" => "Día", "plural" => "Días"],
            ["code" => "today", "label" => "Día de hoy", "plural" => "Días de hoy"],
            ["code" => "month", "label" => "Mes", "plural" => "Meses"],
            ["code" => "year", "label" => "Año", "plural" => "Años"]
        ];

        return Utilities::getValues($types, $type, $code);

    }

    public static function getTypes($type = "all", $code = "") {

        $types = [
            ["code" => "sale", "label" => "Venta"],
            ["code" => "manual", "label" => "Manual"]
        ];

        return Utilities::getValues($types, $type, $code);

    }

    public static function getStatuses($type = "all", $code = "") {

        $statuses = [
            ["code" => "active", "label" => "Activo"],
            ["code" => "inactive", "label" => "Inactivo"],
            ["code" => "canceled", "label" => "Anulado"]
        ];

        return Utilities::getValues($statuses, $type, $code);

    }

    // Relationships
    public function saleHeader() {

        return $this->belongsTo(SaleHeader::class, "sale_header_id", "id");

    }

    public function item() {

        return $this->belongsTo(Item::class, "item_id", "id");

    }

    public function currency() {

        return $this->belongsTo(Currency::class, "currency_id", "id");

    }

    public function customer() {

        return $this->belongsTo(Customer::class, "customer_id", "id");

    }

}
