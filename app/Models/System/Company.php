<?php

namespace App\Models\System;

use App\Helpers\System\Utilities;
use Illuminate\Database\Eloquent\Model;

class Company extends Model {

    protected $table               = "companies";
    protected $primaryKey          = "id";
    public $incrementing           = true;
    public $timestamps             = true;
    public static $snakeAttributes = false;

    protected $appends = [
        "formatted_document_type",
        "formatted_status"
    ];

    protected $fillable = [
        "identity_document_type_id",
        "document_number",
        "legal_name",
        "commercial_name",
        "status"
    ];

    public function getFormattedDocumentTypeAttribute() {

        return self::getDocumentTypes("first", $this->attributes["document_type"])["label"] ?? "";

    }

    public function getFormattedStatusAttribute() {

        return self::getStatusses("first", $this->attributes["status"])["label"] ?? "";

    }

    public static function getDocumentTypes($type = "all", $code = "") {

        $documentTypes = [
            ["code" => "dni", "label" => "DNI"],
            ["code" => "ruc", "label" => "RUC"],
            ["code" => "none", "label" => "Ninguno"]
        ];

        return Utilities::getValues($documentTypes, $type, $code);

    }

    public static function getStatusses($type = "all", $code = "") {

        $statusses = [
            ["code" => "active", "label" => "Activo"],
            ["code" => "inactive", "label" => "Inactivo"]
        ];

        return Utilities::getValues($statusses, $type, $code);

    }

    public function identityDocumentType() {

        return $this->belongsTo(IdentityDocumentType::class, "identity_document_type_id", "id");

    }

    public function socialsMedia() {

        return $this->hasMany(CompanySocialMedia::class, "company_id", "id")
                    ->whereIn("status", ["active"]);

    }

}
