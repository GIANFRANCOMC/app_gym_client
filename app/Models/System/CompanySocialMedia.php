<?php

namespace App\Models\System;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CompanySocialMedia extends Model {

    protected $table               = "company_socials_media";
    protected $primaryKey          = "id";
    public $incrementing           = true;
    public $timestamps             = true;
    public static $snakeAttributes = false;

    protected $appends = [
        //
    ];

    protected $fillable = [
        "company_id",
        "type",
        "link"
    ];

}
