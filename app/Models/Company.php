<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model {

    use HasFactory;

    protected $table               = 'companies';
    protected $primaryKey          = 'id';
    public $incrementing           = true;
    public $timestamps             = true;
    protected $appends             = ['formatted_status'];
    public static $snakeAttributes = false;

    protected $fillable = ['name', 'address', 'status'];

    public function getFormattedStatusAttribute() {

        switch($this->attributes['status']) {
            case 'active':
                return 'activo';
                break;

            case 'inactive':
                return 'inactivo';
                break;

            default:
                return $this->attributes['status'];
                break;
        }

    }

}
