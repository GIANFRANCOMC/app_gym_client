<?php

namespace App\Models\System;

use App\Helpers\System\Utilities;
use Illuminate\Database\Eloquent\Model;

class SaleBody extends Model {

    protected $table               = "sales_body";
    protected $primaryKey          = "id";
    public $incrementing           = true;
    public $timestamps             = true;
    public static $snakeAttributes = true;

    protected $appends = [
        "formatted_duration",
        "formatted_type",
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
        "updated_by"
    ];

    // Appends
    public function getFormattedDurationAttribute() {

        if(Utilities::isDefined($this->duration_type) && Utilities::isDefined($this->duration_value)) {

            $prop = $this->duration_value > 1 ? "plural" : "label";
            $durationType = self::getDurationTypes("first", $this->attributes["duration_type"])[$prop] ?? "";

            return "{$this->duration_value} {$durationType}";

        }

        return "";

    }

    public function getFormattedTypeAttribute() {

        return self::getTypes("first", $this->attributes["type"])["label"] ?? "";

    }

    public function getFormattedStatusAttribute() {

        return self::getStatusses("first", $this->attributes["status"])["label"] ?? "";

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

    public static function getStatusses($type = "all", $code = "") {

        $statusses = [
            ["code" => "active", "label" => "Activo"],
            ["code" => "inactive", "label" => "Inactivo"],
            ["code" => "cancelled", "label" => "Anulado"]
        ];

        return Utilities::getValues($statusses, $type, $code);

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
