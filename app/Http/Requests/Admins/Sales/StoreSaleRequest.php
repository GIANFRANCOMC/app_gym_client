<?php

namespace App\Http\Requests\Admins\Sales;

use Illuminate\Foundation\Http\FormRequest;

class StoreSaleRequest extends FormRequest
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
            'sale_date'   => 'required|string',
            'branch_id'   => 'required|string',
            'customer_id' => 'required|string',
            'details'     => 'required|array',
        ];
    }
}
