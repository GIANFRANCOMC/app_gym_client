<?php

namespace App\Models\System\Customers;

use App\Helpers\System\Utilities;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

use App\Models\System\Organizations\{Branch, Company};

class Attendance extends Model {

    protected $table               = "attendances";
    protected $primaryKey          = "id";
    public $incrementing           = true;
    public $timestamps             = true;
    public static $snakeAttributes = true;

    protected $appends = [
        "worked_hours",
        "formatted_type",
        "formatted_status"
    ];

    protected $fillable = [
        "company_id",
        "branch_id",
        "customer_id",
        "start_date",
        "end_date",
        "observation",
        "motive",
        "type",
        "status",
        "created_at",
        "created_by",
        "updated_at",
        "updated_by",
        "canceled_at",
        "canceled_by"
    ];

    // Appends
    public function getWorkedHoursAttribute() {

        $start = Carbon::parse($this->attributes["start_date"]);
        $end = Carbon::parse($this->attributes["end_date"]);

        return Utilities::isDefined($this->attributes["start_date"]) && Utilities::isDefined($this->attributes["end_date"]) ? round($start->floatDiffInHours($end), 3) : 0;

    }

    public function getFormattedTypeAttribute() {

        return self::getTypes("first", $this->attributes["type"])["label"] ?? "";

    }

    public function getFormattedStatusAttribute() {

        return self::getStatuses("first", $this->attributes["status"])["label"] ?? "";

    }

    // Functions
    public static function getTypes($type = "all", $code = "") {

        $types = [
            ["code" => "manual_form", "label" => "Manual"],
            ["code" => "qr_camera", "label" => "Cámara interna"],
            ["code" => "qr_scanner", "label" => "Escáner externo"],
            ["code" => "qr_public", "label" => "Público"]
        ];

        return Utilities::getValues($types, $type, $code);

    }

    public static function getStatuses($type = "all", $code = "") {

        $statuses = [
            ["code" => "active", "label" => "En curso"],
            ["code" => "canceled", "label" => "Anulada"],
            ["code" => "inactive", "label" => "Inactiva"],
            ["code" => "finalized", "label" => "Concluida"]
        ];

        return Utilities::getValues($statuses, $type, $code);

    }

    // Relationships
    public function company() {

        return $this->belongsTo(Company::class, "company_id", "id");

    }

    public function branch() {

        return $this->belongsTo(Branch::class, "branch_id", "id");

    }

    public function customer() {

        return $this->belongsTo(Customer::class, "customer_id", "id");

    }

}
