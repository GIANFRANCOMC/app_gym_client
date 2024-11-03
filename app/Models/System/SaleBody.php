<?php

namespace App\Models\System;

use App\Helpers\System\Utilities;
use Illuminate\Database\Eloquent\Model;

class SaleBody extends Model {

    protected $table               = "sales_body";
    protected $primaryKey          = "id";
    public $incrementing           = true;
    public $timestamps             = true;
    public static $snakeAttributes = false;

    protected $appends = [
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
        "observation",
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

    public function saleHeader() {

        return $this->belongsTo(SaleHeader::class, "sale_header_id", "id");

    }

    public function item() {

        return $this->belongsTo(Item::class, "item_id", "id");

    }

    public function currency() {

        return $this->belongsTo(Currency::class, "currency_id", "id");

    }

}
