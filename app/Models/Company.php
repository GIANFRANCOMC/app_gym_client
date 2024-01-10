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
    protected $appends             = ['formatted_type_document', 'formatted_status'];
    public static $snakeAttributes = false;

    protected $fillable = ['type_document', 'number_document', 'legal_name', 'commercial_name', 'logo', 'status'];

    public function getFormattedTypeDocumentAttribute() {

        if($this->type_document) {

            $formattedTypeDocument = '';

            switch($this->type_document) {
                case 'dni':
                    $formattedTypeDocument = 'dni';
                    break;

                case 'ruc':
                    $formattedTypeDocument = 'ruc';
                    break;

                case 'none':
                    $formattedTypeDocument = 'ninguno';
                    break;

                default:
                    $formattedTypeDocument = $this->type_document;
                    break;
            }

            return $formattedTypeDocument;

        }

        return null;

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

    public function admins() {

        return $this->hasMany(Admin::class);

    }

    public function branches() {

        return $this->hasMany(Branch::class);

    }

    public function customers() {

        return $this->hasMany(Customer::class);

    }

    public function customerUsers() {

        return $this->hasMany(Customer::class);

    }

    public function productServices() {

        return $this->hasMany(ProductService::class);

    }

    public function users() {

        return $this->hasMany(User::class);

    }

}
