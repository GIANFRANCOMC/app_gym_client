<?php

namespace App\Http\Requests\Admins\Admins;

use Illuminate\Foundation\Http\FormRequest;

class StoreAdminRequest extends FormRequest
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
        return [
            'number_document' => 'required|string|min:2|max:30|unique:admins,number_document',
            'last_name'       => 'required|string|min:2|max:65',
            'first_name'      => 'required|string|min:2|max:85',
            'email'           => 'required|string',
            'password'        => 'required|string',
            'birth_date'      => 'required|date',
            'gender'          => 'required|string',
            'phone'           => 'required|string',
            'status'          => 'required|string'
        ];
    }
}
