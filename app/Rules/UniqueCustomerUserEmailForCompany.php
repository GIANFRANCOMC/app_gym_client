<?php

namespace App\Rules;

use App\Models\CustomerUser;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class UniqueCustomerUserEmailForCompany implements ValidationRule {

    public function __construct() {

        //

    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void {

        $customerUser = CustomerUser::where('email', $value)
                                    ->exists();

        if($customerUser) $fail('Ya ha sido registrado.');

    }

}
