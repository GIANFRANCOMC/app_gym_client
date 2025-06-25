<?php

namespace App\Models\System;

use App\Helpers\System\Utilities;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Category extends Model {

    protected $table               = "categories";
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
        "status",
        "created_at",
        "created_by",
        "updated_at",
        "updated_by"
    ];

    // Appends
    public function getFormattedStatusAttribute() {

        return self::getStatuses("first", $this->attributes["status"] ?? "")["label"] ?? "";

    }

    // Functions
    public static function getStatuses($type = "all", $code = "") {

        $statuses = [
            ["code" => "active", "label" => "Activo"],
            ["code" => "inactive", "label" => "Inactivo"]
        ];

        return Utilities::getValues($statuses, $type, $code);

    }

    public static function getAll($type = "default") {

        $userAuth = Auth::user();

        return Category::where("company_id", $userAuth->company_id)
                       ->when(in_array($type, ["product", "service"]), function($query) {

                            $query->whereIn("status", ["active"])
                                  ->orderBy("name", "ASC");

                       })
                       ->get();

    }

    // Relationships
    public function company() {

        return $this->belongsTo(Company::class, "company_id", "id");

    }

    public function items() {

        return $this->hasMany(CategoryItem::class, "category_id", "id")
                    ->whereIn("status", ["active"]);

    }

}
