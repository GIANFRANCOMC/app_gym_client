<?php

namespace App\Rules;

use App\Models\ProductService;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class UniqueProductServiceNameForCompany implements ValidationRule {

    protected $companyId;

    public function __construct($companyId) {

        $this->companyId = $companyId;

    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void {

        $productService = ProductService::where('name', $value)
                                         ->where('company_id', $this->companyId)
                                         ->exists();

        if($productService) $fail('Ya ha sido registrado.');

    }

}
