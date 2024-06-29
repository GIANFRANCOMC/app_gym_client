<?php

namespace App\Models\Tenant;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class CustomerUser extends Model {

    use HasApiTokens, HasFactory, Notifiable;

    protected $table               = 'customer_users';
    protected $primaryKey          = 'id';
    public $incrementing           = true;
    public $timestamps             = true;
    protected $appends             = ['formatted_status'];
    public static $snakeAttributes = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'customer_id',
        'status'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

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

    public function customer() {

        return $this->belongsTo(Customer::class);

    }

}
