<?php

namespace App\Rules;

use App\Models\ProductService;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class UniqueProductServiceNameForCompany implements ValidationRule {

    public function __construct() {

        //

    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void {

        $productService = ProductService::where('name', $value)
                                         ->exists();

        if($productService) $fail('Ya ha sido registrado.');

    }

}
