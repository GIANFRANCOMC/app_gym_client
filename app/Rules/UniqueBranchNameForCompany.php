<?php

namespace App\Rules;

use App\Models\Branch;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class UniqueBranchNameForCompany implements ValidationRule {

    public function __construct() {

        //

    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void {

        $branch = Branch::where('name', $value)
                        ->exists();

        if($branch) $fail('Ya ha sido registrado.');

    }

}
