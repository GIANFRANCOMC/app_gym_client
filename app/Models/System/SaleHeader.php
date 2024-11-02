<?php

namespace App\Models\System;

use App\Helpers\System\Utilities;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class SaleHeader extends Model {

    protected $table               = "sales_header";
    protected $primaryKey          = "id";
    public $incrementing           = true;
    public $timestamps             = true;
    public static $snakeAttributes = false;

    protected $appends = [
        "formatted_sale_date",
        "legible_total",
        "formatted_status"
    ];

    protected $fillable = [
        "serie_id",
        "sequential",
        "holder_id",
        "seller_id",
        "currency_id",
        "sale_date",
        "total",
        "observation",
        "status"
    ];

    public function getFormattedSaleDateAttribute() {

        return Carbon::createFromFormat("Y-m-d", $this->attributes["sale_date"])->format("d-m-Y");

    }

    public function getLegibleTotalAttribute() {

        return Utilities::convertNumberToWords($this->attributes["total"]);

    }

    public function getFormattedStatusAttribute() {

        return self::getStatusses("first", $this->attributes["status"])["label"] ?? "";

    }

    public static function getStatusses($type = "all", $code = "") {

        $result = null;

        $statusses = [
            ["code" => "active", "label" => "Activo"],
            ["code" => "inactive", "label" => "Inactivo"]
        ];

        if(in_array($type, ["all"])) {

            $result = $statusses;

        }else if(in_array($type, ["first"])) {

            $filter = array_filter($statusses, function($e) use($code) { return $e["code"] === $code; });
            $result = count($filter) > 0 ? reset($filter) : null;

        }

        return $result;

    }

    public static function getSerieSequential(SaleHeader $saleHeader) {

        return $saleHeader->serie->code.$saleHeader->serie->number."-".$saleHeader->sequential;

    }

    public function serie() {

        return $this->belongsTo(Serie::class, "serie_id", "id");

    }

    public function holder() {

        return $this->belongsTo(Customer::class, "holder_id", "id");

    }

    public function seller() {

        return $this->belongsTo(User::class, "seller_id", "id");

    }

    public function currency() {

        return $this->belongsTo(Currency::class, "currency_id", "id");

    }

    public function positions() {

        return $this->hasMany(SaleBody::class, "sale_header_id", "id");

    }

}
