<?php

namespace App\Http\Requests\Admins\Branches;

use App\Rules\UniqueBranchNameForCompany;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreBranchRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $userAuth = Auth::user();

        return [
            'name'     => ['required', 'string', 'min:2', 'max:65', new UniqueBranchNameForCompany($userAuth->company_id)],
            'location' => 'required|string|min:2|max:85',
            'status'   => 'required|string'
        ];
    }
}
