<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model {

    use HasFactory;

    protected $table               = 'companies';
    protected $primaryKey          = 'id';
    public $incrementing           = true;
    public $timestamps             = true;
    protected $appends             = ['formatted_type_document'];
    public static $snakeAttributes = false;

    protected $fillable = [
        'type_document',
        'number_document',
        'legal_name',
        'commercial_name',
        'logo'
    ];

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

}
