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
    public function getFormattedStatusAttribute() {

        return self::getStatusses("first", $this->attributes["status"])["label"] ?? "";

    }

    // Functions
    public static function getStatusses($type = "all", $code = "") {

        $statusses = [
            ["code" => "active", "label" => "Activo"],
            ["code" => "inactive", "label" => "Inactivo"]
        ];

        return Utilities::getValues($statusses, $type, $code);

    }

    public static function getAll() {

        $userAuth = Auth::user();

        return Item::where("company_id", $userAuth->company_id)
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
