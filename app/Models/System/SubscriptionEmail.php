<?php

namespace App\Models\System;

use App\Helpers\System\Utilities;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class SubscriptionEmail extends Model {

    protected $table               = "subscription_emails";
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
        "to",
        "subject",
        "body",
        "extras_json",
        "type",
        "model_id",
        "model_type",
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
            ["code" => "SubscriptionExpired", "label" => "SubscriptionExpired"]
        ];

        return Utilities::getValues($types, $type, $code);

    }

    public static function getStatuses($type = "all", $code = "") {

        $statuses = [
            ["code" => "pending", "label" => "Pendiente"],
            ["code" => "sent", "label" => "Enviado"],
            ["code" => "failed", "label" => "Fallido"]
        ];

        return Utilities::getValues($statuses, $type, $code);

    }

    // Relationships
    public function company() {

        return $this->belongsTo(Company::class, "company_id", "id");

    }

}
