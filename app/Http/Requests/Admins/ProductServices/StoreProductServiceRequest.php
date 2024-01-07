<?php

namespace App\Http\Requests\Admins\ProductServices;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductServiceRequest extends FormRequest
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
            'name'        => 'required|string|min:2|max:65',
            'description' => 'required|string|min:2|max:85',
            'price'       => 'required|string',
            'status'      => 'required|string'
        ];
    }
}
