<?php

namespace App\Models\System;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Company extends Model {

    use HasFactory;

    protected $table               = "companies";
    protected $primaryKey          = "id";
    public $incrementing           = true;
    public $timestamps             = true;
    public static $snakeAttributes = false;

    protected $appends = [
        "formatted_document_type"
    ];

    protected $fillable = [
        "document_type",
        "document_number",
        "legal_name",
        "commercial_name"
    ];

    public function getFormattedTypeDocumentAttribute() {

        return self::getTypeDocument("first", $this->attributes["document_type"])["label"] ?? "";

    }

    public static function getTypeDocument($type = "all", $code = "") {

        $result = null;

        $statusses = [
            ["code" => "dni", "label" => "DNI"],
            ["code" => "ruc", "label" => "RUC"],
            ["code" => "none", "label" => "Ninguno"]
        ];

        if(in_array($type, ["all"])) {

            $result = $statusses;

        }else if(in_array($type, ["first"])) {

            $filter = array_filter($statusses, function($e) use($code) { return $e["code"] === $code; });
            $result = count($filter) > 0 ? reset($filter) : null;

        }

        return $result;

    }

}
