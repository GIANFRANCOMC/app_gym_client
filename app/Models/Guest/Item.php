<?php

namespace App\Models\Guest;

use App\Helpers\System\Utilities;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Item extends Model {

    protected $table               = "items";
    protected $primaryKey          = "id";
    public $incrementing           = true;
    public $timestamps             = true;
    public static $snakeAttributes = true;

    protected $appends = [
        "formatted_type",
        "formatted_duration",
        "formatted_status"
    ];

    protected $fillable = [
        "company_id",
        "internal_code",
        "name",
        "description",
        "price",
        "currency_id",
        "type",
        "duration_type",
        "duration_value",
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

    public function getFormattedDurationAttribute() {

        if(Utilities::isDefined($this->duration_type) && Utilities::isDefined($this->duration_value)) {

            $prop = $this->duration_value > 1 ? "plural" : "label";
            $durationType = self::getDurationTypes("first", $this->attributes["duration_type"])[$prop] ?? "";

            return "{$this->duration_value} {$durationType}";

        }

        return "";

    }

    public function getFormattedStatusAttribute() {

        return self::getStatuses("first", $this->attributes["status"])["label"] ?? "";

    }


    public function getStockQuantityAttribute($value) {

        return $value ?? 0;

    }

    // Functions
    public static function getTypes($type = "all", $code = "") {

        $types = [
            ["code" => "product", "label" => "Producto"],
            ["code" => "service", "label" => "Servicio"],
            ["code" => "subscription", "label" => "Membresía"]
        ];

        return Utilities::getValues($types, $type, $code);

    }

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

    public static function getStatuses($type = "all", $code = "") {

        $statuses = [
            ["code" => "active", "label" => "Activo"],
            ["code" => "inactive", "label" => "Inactivo"]
        ];

        return Utilities::getValues($statuses, $type, $code);

    }

    public static function getAll($type = "default", $company) {

        return Item::where("company_id", $company->id)
                   ->when(in_array($type, ["home"]), function($query) {

                        $query->whereIn("status", ["active"])
                              ->orderBy("type", "DESC")
                              ->orderBy("name", "ASC");

                   })
                   ->with(["currency"])
                   ->get();

    }

    // Relationships
    public function company() {

        return $this->belongsTo(Company::class, "company_id", "id");

    }

    public function currency() {

        return $this->belongsTo(Currency::class, "currency_id", "id");

    }

}
