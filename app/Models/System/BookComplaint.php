<?php

namespace App\Models\System;

use App\Helpers\System\Utilities;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class BookComplaint extends Model {

    protected $table               = "book_complaints";
    protected $primaryKey          = "id";
    public $incrementing           = true;
    public $timestamps             = true;
    public static $snakeAttributes = true;

    protected $appends = [
        "formatted_type",
        "formatted_status",
        "formatted_created_at",
        "formatted_updated_at"
    ];

    protected $fillable = [
        "company_id",
        "identity_document_type_id",
        "document_number",
        "name",
        "email",
        "phone_number",
        "type",
        "description",
        "request",
        "evidence",
        "admin_response",
        "submitted_ip",
        "submitted_user_agent",
        "submitted_platform",
        "submitted_browser",
        "status",
        "created_at",
        "created_by",
        "updated_at",
        "updated_by"
    ];

    // Appends
    public function getFormattedTypeAttribute() {

        return self::getTypes("first", $this->attributes["type"])["label"] ?? "";

    }

    public function getFormattedStatusAttribute() {

        return self::getStatuses("first", $this->attributes["status"])["label"] ?? "";

    }

    public function getFormattedCreatedAtAttribute() {

        return Carbon::parse($this->attributes["created_at"])->format("Y-m-d H:i:s");

    }

    public function getFormattedUpdatedAtAttribute() {

        return Carbon::parse($this->attributes["updated_at"])->format("Y-m-d H:i:s");

    }

    // Functions
    public static function getTypes($type = "all", $code = "") {

        $types = [
            ["code" => "complaint", "label" => "Queja", "description" => "Cuéntanos algo que no te gustó.", "icon" => "fa-solid fa-exclamation-circle", "color" => "warning"],
            ["code" => "claim", "label" => "Reclamo", "description" => "Pide que solucionemos un problema.", "icon" => "fa-solid fa-gavel", "color" => "danger"],
            ["code" => "suggestion", "label" => "Sugerencia", "description" => "Idea o recomendación para mejorar.", "icon" => "fa-solid fa-lightbulb", "color" => "info"]
        ];

        return Utilities::getValues($types, $type, $code);

    }

    public static function getStatuses($type = "all", $code = "") {

        $statuses = [
            ["code" => "pending", "label" => "Aún no ha sido revisado"],
            ["code" => "in_progress", "label" => "Está en proceso de atención"],
            ["code" => "resolved", "label" => "Ya ha sido atendido y cerrado"]
        ];

        return Utilities::getValues($statuses, $type, $code);

    }

    // Relationships
    public function company() {

        return $this->belongsTo(Company::class, "company_id", "id");

    }

    public function identityDocumentType() {

        return $this->belongsTo(IdentityDocumentType::class, "identity_document_type_id", "id");

    }

}
