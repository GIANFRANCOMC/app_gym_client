<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BranchUser extends Model
{
    use HasFactory;

    protected $table               = 'branch_users';
    protected $primaryKey          = 'id';
    public $incrementing           = true;
    public $timestamps             = true;
    protected $appends             = ['formatted_status'];
    public static $snakeAttributes = false;

    protected $fillable = ['branch_id', 'user_id', 'status'];

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

    public function branch() {

        return $this->belongsTo(Branch::class);

    }

    public function user() {

        return $this->belongsTo(User::class);

    }

}
