<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCustomerRequest extends FormRequest
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
            'number_document' => 'required|string|min:2|max:30|unique:customers,number_document',
            'last_name'       => 'required|string|min:2|max:60',
            'first_name'      => 'required|string|min:2|max:60',
            'birth_date'      => 'required|date',
            'status'          => 'required|string'
        ];
    }
}
