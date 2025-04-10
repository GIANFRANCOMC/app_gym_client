<?php

namespace App\Models\System;

use App\Helpers\System\Utilities;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Branch extends Model {

    protected $table               = "branches";
    protected $primaryKey          = "id";
    public $incrementing           = true;
    public $timestamps             = true;
    public static $snakeAttributes = true;

    protected $appends = [
        "formatted_status"
    ];

    protected $fillable = [
        "company_id",
        "name",
        "status",
        "created_at",
        "created_by",
        "updated_at",
        "updated_by"
    ];

    // Appends
    public function getFormattedStatusAttribute() {

        return self::getStatuses("first", $this->attributes["status"])["label"] ?? "";

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

        return Branch::where("company_id", $userAuth->company_id)
                     ->when(in_array($type, ["sale"]), function($query) {

                            $query->whereIn("status", ["active"]);

                     })
                     ->when(in_array($type, ["asset_management"]), function($query) {

                        // $query->whereIn("status", ["active"]);

                     })
                     ->get();

    }

    // Relationships
    public function company() {

        return $this->belongsTo(Company::class, "company_id", "id");

    }

    public function assets() {

        return $this->hasMany(BranchAsset::class, "branch_id", "id")
                    ->whereIn("status", ["active"]);

    }

    public function assetsAll() {

        return $this->hasMany(BranchAsset::class, "branch_id", "id");

    }

    public function series() {

        return $this->hasMany(Serie::class, "branch_id", "id")
                    ->whereIn("status", ["active"]);

    }

    public function warehouses() {

        return $this->hasMany(Warehouse::class, "branch_id", "id")
                    ->whereIn("status", ["active"]);

    }

    public function warehousesAll() {

        return $this->hasMany(Warehouse::class, "branch_id", "id");

    }

}
