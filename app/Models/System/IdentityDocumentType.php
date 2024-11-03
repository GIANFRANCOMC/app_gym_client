<?php

namespace App\Models\System;

use App\Helpers\System\Utilities;
use Illuminate\Database\Eloquent\Model;

class IdentityDocumentType extends Model {

    protected $table               = "identity_document_types";
    protected $primaryKey          = "id";
    public $incrementing           = true;
    public $timestamps             = true;
    public static $snakeAttributes = false;

    protected $appends = [
        "formatted_status"
    ];

    protected $fillable = [
        "name",
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

    public function companies() {

        return $this->hasMany(Company::class, "identity_document_type_id", "id")
                    ->whereIn("status", ["active"]);

    }

    public function customers() {

        return $this->hasMany(Customer::class, "identity_document_type_id", "id")
                    ->whereIn("status", ["active"]);

    }

    public function users() {

        return $this->hasMany(User::class, "identity_document_type_id", "id")
                    ->whereIn("status", ["active"]);

    }

}
