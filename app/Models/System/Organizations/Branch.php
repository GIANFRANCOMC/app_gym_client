<?php

namespace App\Models\System\Organizations;

use App\Helpers\System\Utilities;
use Illuminate\Database\Eloquent\Model;

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
        "address",
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

    public static function getAll($type = "default", $company_id = null) {

        return Branch::where("company_id", $company_id)
                     ->when(in_array($type, ["sale"]), function($query) {

                        $query->whereIn("status", ["active"]);

                     })
                     ->when(in_array($type, ["tracking_subscription"]), function($query) {

                        // $query->whereIn("status", ["active"]);

                     })
                     ->when(in_array($type, ["tracking_attendance"]), function($query) {

                        // $query->whereIn("status", ["active"]);

                     })
                     ->when(in_array($type, ["asset_management"]), function($query) {

                        // $query->whereIn("status", ["active"]);

                     })
                     ->with(["series.documentType"])
                     ->get();

    }

    // Relationships
    public function company() {

        return $this->belongsTo(Company::class, "company_id", "id");

    }

}
