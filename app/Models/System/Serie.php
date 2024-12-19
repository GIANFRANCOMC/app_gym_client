<?php

namespace App\Models\System;

use App\Helpers\System\Utilities;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Serie extends Model {

    protected $table               = "series";
    protected $primaryKey          = "id";
    public $incrementing           = true;
    public $timestamps             = true;
    public static $snakeAttributes = true;

    protected $appends = [
        "legible_serie",
        "formatted_status"
    ];

    protected $fillable = [
        "branch_id",
        "document_type_id",
        "code",
        "number",
        "init",
        "status",
        "created_at",
        "created_by",
        "updated_at",
        "updated_by"
    ];

    // Appends
    public function getLegibleSerieAttribute() {

        return $this->attributes["code"].$this->attributes["number"];

    }

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

    public static function getNewSequential() {

        $userAuth = Auth::user();

        $newSequential = 0;

        try {

            $maxSequential = Branch::where("company_id", $userAuth->company_id)
                                   ->count();

            $newSequential = intval($maxSequential) + 1;

        }catch(Exception $e) {

            $newSequential = 0;

        }

        return $newSequential;

    }

    // Relationships
    public function branch() {

        return $this->belongsTo(Branch::class, "branch_id", "id");

    }

    public function documentType() {

        return $this->belongsTo(DocumentType::class, "document_type_id", "id");

    }

    public function salesHeader() {

        return $this->hasMany(SaleHeader::class, "serie_id", "id")
                    ->whereIn("status", ["active"]);

    }

}
