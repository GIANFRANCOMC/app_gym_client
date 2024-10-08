<?php

namespace App\Models\System;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Item extends Model {

    use HasFactory;

    protected $table               = "items";
    protected $primaryKey          = "id";
    public $incrementing           = true;
    public $timestamps             = true;
    public static $snakeAttributes = false;

    protected $appends = [
        "formatted_status"
    ];

    protected $fillable = [
        "name",
        "description",
        "price",
        "currency_id",
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

    public function currency() {

        return $this->belongsTo(Currency::class, "currency_id", "id");

    }

}
