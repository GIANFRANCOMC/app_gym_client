<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $table               = 'customers';
    protected $primaryKey          = 'id';
    public $incrementing           = true;
    public $timestamps             = true;
    protected $appends             = ['formatted_birth_date', 'formatted_status', 'age'];
    public static $snakeAttributes = false;

    protected $fillable = ['type_document', 'number_document', 'last_name', 'first_name', 'birth_date', 'status'];

    // Accessor method to format birth_date
    public function getFormattedBirthDateAttribute()
    {
        // Check if 'birth_date' is not null
        if ($this->birth_date) {
            // Create Carbon instance from 'birth_date'
            $birthDate = Carbon::parse($this->birth_date);
            // Format the date as dd-mm-yyyy
            return $birthDate->format('d-m-Y');
        }

        // If 'birth_date' is null, return null
        return null;
    }

    public function getFormattedStatusAttribute()
    {
        // You can customize the formatting based on your needs
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

    // Accessor method to calculate age based on birth_date
    public function getAgeAttribute()
    {
        // Check if 'birth_date' is not null
        if ($this->birth_date) {
            // Create Carbon instance from 'birth_date'
            $birthDate = Carbon::parse($this->birth_date);
            // Calculate age
            return $birthDate->age;
        }

        // If 'birth_date' is null, return null
        return null;
    }

}
