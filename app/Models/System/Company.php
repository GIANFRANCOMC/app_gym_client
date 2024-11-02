<?php

namespace App\Models\System;

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

        $result = null;

        $documentTypes = [
            ["code" => "dni", "label" => "DNI"],
            ["code" => "ruc", "label" => "RUC"],
            ["code" => "none", "label" => "Ninguno"]
        ];

        if(in_array($type, ["all"])) {

            $result = $documentTypes;

        }else if(in_array($type, ["first"])) {

            $filter = array_filter($documentTypes, function($e) use($code) { return $e["code"] === $code; });
            $result = count($filter) > 0 ? reset($filter) : null;

        }

        return $result;

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

    public function socialsMedia() {

        return $this->hasMany(CompanySocialMedia::class, "company_id", "id");

    }

    public function identityDocumentType() {

        return $this->belongsTo(IdentityDocumentType::class, "identity_document_type_id", "id");

    }

}
