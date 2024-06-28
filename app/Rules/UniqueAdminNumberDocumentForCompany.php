<?php

namespace App\Rules;

use App\Models\Admin;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class UniqueAdminNumberDocumentForCompany implements ValidationRule {

    public function __construct() {

        //

    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void {

        $admin = Admin::where('number_document', $value)
                      ->exists();

        if($admin) $fail('Ya ha sido registrado.');

    }

}
