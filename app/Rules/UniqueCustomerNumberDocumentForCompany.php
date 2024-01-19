<?php

namespace App\Rules;

use App\Models\Customer;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class UniqueCustomerNumberDocumentForCompany implements ValidationRule {

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

        $customer = Customer::where('number_document', $value)
                            ->where('company_id', $this->companyId)
                            ->exists();

        if($customer) $fail('Ya ha sido registrado.');

    }

}
