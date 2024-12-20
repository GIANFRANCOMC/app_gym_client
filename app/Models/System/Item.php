<?php

namespace App\Models\System;

use App\Helpers\System\Utilities;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Auth;

class Item extends Model {

    use HasFactory;

    protected $table               = "items";
    protected $primaryKey          = "id";
    public $incrementing           = true;
    public $timestamps             = true;
    public static $snakeAttributes = true;

    protected $appends = [
        "formatted_type",
        "formatted_status"
    ];

    protected $fillable = [
        "company_id",
        "internal_code",
        "name",
        "description",
        "price",
        "currency_id",
        "type",
        "status",
        "created_at",
        "created_by",
        "updated_at",
        "updated_by"
    ];

    // Appends
    public function getFormattedTypeAttribute() {

        return self::getTypes("first", $this->attributes["type"])["label"] ?? "";

    }

    public function getFormattedStatusAttribute() {

        return self::getStatusses("first", $this->attributes["status"])["label"] ?? "";

    }

    // Functions
    public static function getTypes($type = "all", $code = "") {

        $types = [
            ["code" => "product", "label" => "Producto"],
            ["code" => "service", "label" => "Servicio"]
        ];

        return Utilities::getValues($types, $type, $code);

    }

    public static function getStatusses($type = "all", $code = "") {

        $statusses = [
            ["code" => "active", "label" => "Activo"],
            ["code" => "inactive", "label" => "Inactivo"]
        ];

        return Utilities::getValues($statusses, $type, $code);

    }

    public static function getAll($type = "default") {

        $userAuth = Auth::user();

        return Item::where("company_id", $userAuth->company_id)
                   ->when(in_array($type, ["sale"]), function($query) {

                        $query->whereIn("status", ["active"]);

                   })
                   ->with(["currency"])
                   ->get();

    }

    // Relationships
    public function company() {

        return $this->belongsTo(Company::class, "company_id", "id");

    }

    public function currency() {

        return $this->belongsTo(Currency::class, "currency_id", "id");

    }

    public function salesBody() {

        return $this->hasMany(SaleBody::class, "item_id", "id")
                    ->whereIn("status", ["active"]);

    }

}
