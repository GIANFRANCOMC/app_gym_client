<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Membership extends Model {

    use HasFactory;

    protected $table               = 'memberships';
    protected $primaryKey          = 'id';
    public $incrementing           = true;
    public $timestamps             = true;
    protected $appends             = ['formatted_type', 'formatted_status'];
    public static $snakeAttributes = false;

    protected $fillable = ['name', 'description', 'price', 'type', 'duration_quantity', 'status'];

    public function getFormattedTypeAttribute() {

        switch($this->attributes['type']) {
            case 'daily':
                return 'diario';
                break;

            case 'monthly':
                return 'mensual';
                break;

            case 'annual':
                return 'anual';
                break;

            default:
                return $this->attributes['type'];
                break;
        }

    }

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
