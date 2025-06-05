<?php

namespace App\Models\System;

use App\Helpers\System\Utilities;
use Exception;
use Illuminate\Database\Eloquent\Model;

class Company extends Model {

    protected $table               = "companies";
    protected $primaryKey          = "id";
    public $incrementing           = true;
    public $timestamps             = true;
    public static $snakeAttributes = true;

    protected $appends = [
        "formatted_status"
    ];

    protected $fillable = [
        "slug",
        "internal_code",
        "identity_document_type_id",
        "document_number",
        "legal_name",
        "commercial_name",
        "tagline",
        "description",
        "address",
        "telephone",
        "email",
        "token_api_misc",
        "logotype",
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

    public static function getActiveSections($company_id) {

        $sections = collect();

        if(!Utilities::isDefined($company_id)) {

            return $sections;

        }

        try {

            $sections = Section::with(["subSections" => function ($query)use($company_id) {

                                    $query->whereHas("companiesSubSections", function($q) use($company_id) {

                                                $q->where("company_id", $company_id);

                                          })
                                          ->with(["companiesSubSections" => function ($q) use ($company_id) {

                                                $q->where("company_id", $company_id);

                                          }]);

                               }])
                               ->whereHas("subSections.companiesSubSections", function($q) use($company_id) {

                                    $q->where("company_id", $company_id);

                               })
                               ->orderBy("order", "ASC")
                               ->get()
                               ->map(function($section) {

                                    $section->subSections->map(function($subSection) {

                                        $subSection->dom_route_url = route($subSection->dom_route);

                                        return $subSection;

                                    });

                                    return $section;

                               });

        }catch(Exception $e) {

            //

        }

        return $sections;

    }

    // Relationships
    public function identityDocumentType() {

        return $this->belongsTo(IdentityDocumentType::class, "identity_document_type_id", "id");

    }

    public function attendances() {

        return $this->hasMany(Attendance::class, "company_id", "id")
                    ->whereIn("status", ["active"]);

    }

    public function branches() {

        return $this->hasMany(Branch::class, "company_id", "id")
                    ->whereIn("status", ["active"]);

    }

    public function companySubSections() {

        return $this->hasMany(CompanySubSection::class, "company_id", "id")
                    ->whereIn("status", ["active"]);

    }

    public function customers() {

        return $this->hasMany(Customer::class, "company_id", "id")
                    ->whereIn("status", ["active"]);

    }

    public function items() {

        return $this->hasMany(Item::class, "company_id", "id")
                    ->whereIn("status", ["active"]);

    }

    public function socialsMedia() {

        return $this->hasMany(CompanySocialMedia::class, "company_id", "id")
                    ->whereIn("status", ["active"]);

    }

}
