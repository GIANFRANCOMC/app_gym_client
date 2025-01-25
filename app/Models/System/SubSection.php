<?php

namespace App\Models\System;

use App\Helpers\System\Utilities;
use Illuminate\Database\Eloquent\Model;

class SubSection extends Model {

    protected $table               = "sub_sections";
    protected $primaryKey          = "id";
    public $incrementing           = true;
    public $timestamps             = true;
    public static $snakeAttributes = true;

    protected $appends = [
        "formatted_status"
    ];

    protected $fillable = [
        "section_id",
        "slug",
        "name",
        "order",
        "dom_id",
        "dom_label",
        "dom_icon",
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

    // Relationships
    public function section() {

        return $this->belongsTo(Section::class, "section_id", "id");

    }

    public function companiesSubSections() {

        return $this->hasMany(CompanySubSection::class, "sub_section_id", "id")
                    ->whereIn("status", ["active"]);

    }

}
