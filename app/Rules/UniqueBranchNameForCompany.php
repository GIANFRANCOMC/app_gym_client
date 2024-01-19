<?php

namespace App\Rules;

use App\Models\Branch;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class UniqueBranchNameForCompany implements ValidationRule {

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

        $branch = Branch::where('name', $value)
                        ->where('company_id', $this->companyId)
                        ->exists();

        if($branch) $fail('Ya ha sido registrado.');

    }

}
