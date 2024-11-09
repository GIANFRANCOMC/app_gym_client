<?php

namespace App\Models\System;

use App\Helpers\System\Utilities;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Exception;

class SaleHeader extends Model {

    protected $table               = "sales_header";
    protected $primaryKey          = "id";
    public $incrementing           = true;
    public $timestamps             = true;
    public static $snakeAttributes = true;

    protected $appends = [
        "formatted_issue_date",
        "legible_total",
        "formatted_status"
    ];

    protected $fillable = [
        "serie_id",
        "sequential",
        "holder_id",
        "seller_id",
        "currency_id",
        "issue_date",
        "total",
        "observation",
        "status",
        "created_at",
        "created_by",
        "updated_at",
        "updated_by"
    ];

    // Appends
    public function getFormattedIssueDateAttribute() {

        return Carbon::createFromFormat("Y-m-d", $this->attributes["issue_date"])->format("d-m-Y");

    }

    public function getLegibleTotalAttribute() {

        return Utilities::convertNumberToWords($this->attributes["total"]);

    }

    public function getFormattedStatusAttribute() {

        return self::getStatusses("first", $this->attributes["status"])["label"] ?? "";

    }

    // Functions
    public static function getStatusses($type = "all", $code = "") {

        $statusses = [
            ["code" => "active", "label" => "Activo"],
            ["code" => "inactive", "label" => "Inactivo"]
        ];

        return Utilities::getValues($statusses, $type, $code);

    }

    // Relationships
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

        return $this->hasMany(SaleBody::class, "sale_header_id", "id")
                    ->whereIn("status", ["active"]);

    }

    // Attributes
    public function getSerieSequentialAttribute() {

        $serie_sequential = "";

        try {

            $serie_sequential = $this->serie->code.$this->serie->number."-".str_pad($this->sequential, 8, "0", STR_PAD_LEFT);

        }catch(Exception $e) {

            $serie_sequential = "Error";

        }

        return $serie_sequential;

    }

}
