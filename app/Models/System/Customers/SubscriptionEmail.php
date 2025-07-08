<?php

namespace App\Models\System\Customers;

use App\Helpers\System\Utilities;
use Illuminate\Database\Eloquent\Model;

use App\Models\System\Organizations\{Company};

class SubscriptionEmail extends Model {

    protected $table               = "subscription_emails";
    protected $primaryKey          = "id";
    public $incrementing           = true;
    public $timestamps             = true;
    public static $snakeAttributes = true;

    protected $appends = [
        "formatted_extras_json",
        "formatted_type",
        "formatted_status"
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
    public function getFormattedExtrasJsonAttribute() {

        return json_decode($this->attributes["extras_json"] ?? "");

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
