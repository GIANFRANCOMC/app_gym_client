<?php

namespace App\Models\System\Customers;

use App\Helpers\System\Utilities;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Facades\{DB};

use App\Models\System\General\{IdentityDocumentType};
use App\Models\System\Organizations\{Branch, Company};
use App\Models\System\Sales\{SaleHeader};

class Customer extends Model {

    protected $table               = "customers";
    protected $primaryKey          = "id";
    public $incrementing           = true;
    public $timestamps             = true;
    public static $snakeAttributes = true;

    protected $appends = [
        "formatted_gender",
        "formatted_status"
    ];

    protected $fillable = [
        "company_id",
        "identity_document_type_id",
        "document_number",
        "name",
        "email",
        "phone_number",
        "gender",
        "gender_description",
        "birthdate",
        "status",
        "created_at",
        "created_by",
        "updated_at",
        "updated_by"
    ];

    // Appends
    public function getFormattedGenderAttribute() {

        return self::getGenders("first", $this->attributes["gender"])["label"] ?? "";

    }

    public function getFormattedStatusAttribute() {

        return self::getStatuses("first", $this->attributes["status"])["label"] ?? "";

    }

    // Functions
    public static function getGenders($type = "all", $code = "") {

        $statuses = [
            ["code" => "male", "label" => "Masculino"],
            ["code" => "female", "label" => "Femenino"],
            ["code" => "other", "label" => "Otro"]
        ];

        return Utilities::getValues($statuses, $type, $code);

    }

    public static function getStatuses($type = "all", $code = "") {

        $statuses = [
            ["code" => "active", "label" => "Activo"],
            ["code" => "inactive", "label" => "Inactivo"]
        ];

        return Utilities::getValues($statuses, $type, $code);

    }

    public function subscriptionEndDates(): array {

        $today = Carbon::now();

        // Currently active subscription per branch (today within range)
        $currentSubscriptions = Subscription::where("customer_id", $this->id)
                                            ->where("status", "active")
                                            ->where("start_date", "<=", $today)
                                            ->where("end_date", ">=", $today)
                                            ->select("branch_id", DB::raw("MAX(end_date) as max_end_date"))
                                            ->groupBy("branch_id")
                                            ->pluck("max_end_date", "branch_id");

        $branchesCurrent = [];

        foreach($currentSubscriptions as $branchId => $maxEndDate) {

            $branch = Branch::where("id", $branchId)
                            ->first();

            if(Utilities::isDefined($branch)) {

                $branchesCurrent[] = [
                    "branch" => [
                        "id" => $branch->id,
                        "name" => $branch->name,
                        "address" => $branch->address
                    ],
                    "max_end_date" => $maxEndDate
                ];

            }

        }

        return $branchesCurrent;

    }

    public static function getAll($type = "default", $company_id = null) {

        return Customer::where("company_id", $company_id)
                       ->when(in_array($type, ["sale"]), function($query) {

                            $query->whereIn("status", ["active"]);

                       })
                       ->when(in_array($type, ["tracking_subscription"]), function($query) {

                            // $query->whereIn("status", ["active"]);

                       })
                       ->when(in_array($type, ["tracking_attendance"]), function($query) {

                            // $query->whereIn("status", ["active"]);

                       })
                       ->get();

    }

    // Relationships
    public function company() {

        return $this->belongsTo(Company::class, "company_id", "id");

    }

    public function identityDocumentType() {

        return $this->belongsTo(IdentityDocumentType::class, "identity_document_type_id", "id");

    }

    public function attendancesAll() {

        return $this->hasMany(Attendance::class, "customer_id", "id");

    }

    public function salesHeader() {

        return $this->hasMany(SaleHeader::class, "holder_id", "id")
                    ->whereIn("status", ["active"]);

    }

}
