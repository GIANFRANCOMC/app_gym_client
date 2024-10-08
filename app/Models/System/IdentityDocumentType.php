<?php

namespace App\Models\System;

use Illuminate\Database\Eloquent\Model;

class IdentityDocumentType extends Model {

    protected $table               = "identity_document_types";
    protected $primaryKey          = "id";
    public $incrementing           = true;
    public $timestamps             = true;
    public static $snakeAttributes = false;

}
