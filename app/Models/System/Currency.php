<?php

namespace App\Models\System;

use App\Helpers\System\Utilities;
use Illuminate\Database\Eloquent\Model;

class Currency extends Model {

    protected $table               = "currencies";
    protected $primaryKey          = "id";
    public $incrementing           = true;
    public $timestamps             = true;
    public static $snakeAttributes = false;

    protected $appends = [
        "formatted_status"
    ];

    protected $fillable = [
        "code",
        "sign",
        "singular_name",
        "plural_name",
        "status"
    ];

    public function getFormattedStatusAttribute() {

        return self::getStatusses("first", $this->attributes["status"])["label"] ?? "";

    }

    public static function getStatusses($type = "all", $code = "") {

        $statusses = [
            ["code" => "active", "label" => "Activo"],
            ["code" => "inactive", "label" => "Inactivo"]
        ];

        return Utilities::getValues($statusses, $type, $code);

    }

    public function items() {

        return $this->hasMany(Item::class, "currency_id", "id")
                    ->whereIn("status", ["active"]);

    }

    public function salesBody() {

        return $this->hasMany(SaleBody::class, "currency_id", "id")
                    ->whereIn("status", ["active"]);

    }

    public function salesHeader() {

        return $this->hasMany(SaleHeader::class, "currency_id", "id")
                    ->whereIn("status", ["active"]);

    }

}
