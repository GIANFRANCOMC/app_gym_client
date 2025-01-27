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
        "identity_document_type_id",
        "document_number",
        "legal_name",
        "commercial_name",
        "address",
        "telephone",
        "email",
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

    public static function getActiveSections($company_id) {

        $sections = collect();

        if(!Utilities::isDefined($company_id)) {

            return $sections;

        }

        try {

            $companySubSections = CompanySubSection::where("company_id", $company_id)
                                                   ->whereHas("subSection.section", function($query) { $query->where("status", "active"); })
                                                   ->with("subSection.section")
                                                   ->get();

            $sections = $companySubSections->pluck("subSection")
                                           ->filter(fn($subSection) => $subSection->status === "active")
                                           ->groupBy(fn($subSection) => $subSection->section->id)
                                           ->map(fn($subSections, $sectionId) => [
                                               "section" => $subSections->first()->section,
                                               "subSections" => $subSections,
                                           ])
                                           ->sortBy(fn($section) => $section["section"]->order);

        }catch(Exception $e) {

            //

        }

        return $sections;

    }

    // Relationships
    public function identityDocumentType() {

        return $this->belongsTo(IdentityDocumentType::class, "identity_document_type_id", "id");

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
