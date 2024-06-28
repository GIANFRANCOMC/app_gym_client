<?php

namespace App\Http\Requests\Admins\Memberships;

use App\Rules\UniqueMembershipNameForCompany;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreMembershipRequest extends FormRequest
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
            'name'              => ['required', 'string', 'min:2', 'max:65', new UniqueMembershipNameForCompany()],
            'description'       => 'required|string|min:2|max:85',
            'duration_quantity' => 'required|integer|min:1|max:999999999',
            'type'              => 'required|string',
            'price'             => 'required|numeric|min:0.1|max:999999999.99|decimal:0,2',
            'status'            => 'required|string'
        ];
    }
}
