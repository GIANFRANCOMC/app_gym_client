<?php

namespace App\Rules;

use App\Models\User;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class UniqueUserEmailForCompany implements ValidationRule {

    public function __construct() {

        //

    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void {

        $user = User::where('email', $value)
                    ->exists();

        if($user) $fail('Ya ha sido registrado.');

    }

}
