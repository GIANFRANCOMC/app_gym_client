<?php

namespace App\Models\Tenant;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model {

    use HasFactory;

    protected $table               = 'customers';
    protected $primaryKey          = 'id';
    public $incrementing           = true;
    public $timestamps             = true;
    protected $appends             = ['formatted_type_document', 'formatted_birth_date', 'formatted_gender', 'formatted_status', 'age'];
    public static $snakeAttributes = false;

    protected $fillable = ['type_document', 'number_document', 'last_name', 'first_name', 'birth_date', 'gender', 'phone', 'status'];

    public function getFormattedTypeDocumentAttribute() {

        if($this->type_document) {

            $formattedTypeDocument = '';

            switch($this->type_document) {
                case 'dni':
                    $formattedTypeDocument = 'dni';
                    break;

                default:
                    $formattedTypeDocument = $this->type_document;
                    break;
            }

            return $formattedTypeDocument;

        }

        return null;

    }

    public function getFormattedBirthDateAttribute() {

        if($this->birth_date) {

            $birthDate = Carbon::parse($this->birth_date);

            return $birthDate->format('d-m-Y');

        }

        return null;

    }

    public function getFormattedGenderAttribute() {

        switch($this->attributes['gender']) {
            case 'male':
                return 'masculino';
                break;

            case 'female':
                return 'femenino';
                break;

            case 'other':
                return 'otro';
                break;

            default:
                return $this->attributes['gender'];
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

    public function getAgeAttribute() {

        if($this->birth_date) {

            $birthDate = Carbon::parse($this->birth_date);

            return $birthDate->age;

        }

        return null;

    }

    public function customerUser() {

        return $this->hasOne(CustomerUser::class);

    }

}
