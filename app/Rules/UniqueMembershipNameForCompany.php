<?php

namespace App\Rules;

use App\Models\Membership;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class UniqueMembershipNameForCompany implements ValidationRule {

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

        $membership = Membership::where('name', $value)
                                ->where('company_id', $this->companyId)
                                ->exists();

        if($membership) $fail('Ya ha sido registrado.');

    }

}
