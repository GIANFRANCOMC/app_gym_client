<?php

namespace App\Models\System\Organizations;

use App\Helpers\System\Utilities;
use Illuminate\Database\Eloquent\Model;
use Exception;

use App\Models\System\General\{DocumentType};

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

    public static function getNewSequential($company_id) {

        $newSequential = 0;

        try {

            $maxSequential = Branch::where("company_id", $company_id)
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

}
