<?php

namespace App\Models\System;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Customer extends Model {

    use HasFactory;

    protected $table               = "customers";
    protected $primaryKey          = "id";
    public $incrementing           = true;
    public $timestamps             = true;
    public static $snakeAttributes = false;

    protected $appends = [
        "formatted_status"
    ];

    protected $fillable = [
        "document_type",
        "document_number",
        "name",
        "status"
    ];

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

}
