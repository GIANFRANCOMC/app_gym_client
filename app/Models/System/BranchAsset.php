<?php

namespace App\Models\System;

use App\Helpers\System\Utilities;
use Illuminate\Database\Eloquent\Model;

class BranchAsset extends Model {

    protected $table               = "branch_assets";
    protected $primaryKey          = "id";
    public $incrementing           = true;
    public $timestamps             = true;
    public static $snakeAttributes = true;

    protected $appends = [
        "formatted_status"
    ];

    protected $fillable = [
        "branch_id",
        "asset_id",
        "currency_id",
        "quantity",
        "acquisition_value",
        "acquisition_date",
        "note",
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
            ["code" => "maintenance", "label" => "En mantenimiento"],
            ["code" => "inactive", "label" => "Retirado"]
        ];

        return Utilities::getValues($statuses, $type, $code);

    }

    // Relationships
    public function branch() {

        return $this->belongsTo(Branch::class, "branch_id", "id");

    }

    public function asset() {

        return $this->belongsTo(Asset::class, "asset_id", "id");

    }

    public function currency() {

        return $this->belongsTo(Currency::class, "currency_id", "id");

    }

}
