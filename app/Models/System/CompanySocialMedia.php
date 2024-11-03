<?php

namespace App\Models\System;

use App\Helpers\System\Utilities;
use Illuminate\Database\Eloquent\Model;

class CompanySocialMedia extends Model {

    protected $table               = "company_socials_media";
    protected $primaryKey          = "id";
    public $incrementing           = true;
    public $timestamps             = true;
    public static $snakeAttributes = false;

    protected $appends = [
        "formatted_status"
    ];

    protected $fillable = [
        "company_id",
        "type",
        "link",
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

    public function company() {

        return $this->belongsTo(Company::class, "company_id", "id");

    }

}
