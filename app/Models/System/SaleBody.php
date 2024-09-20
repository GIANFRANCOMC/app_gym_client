<?php

namespace App\Models\System;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SaleBody extends Model {

    use HasFactory;

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
        "name",
        "price",
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
