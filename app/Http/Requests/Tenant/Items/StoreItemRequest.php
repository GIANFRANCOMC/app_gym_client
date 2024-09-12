<?php

namespace App\Http\Requests\Tenant\Items;

use Illuminate\Foundation\Http\FormRequest;

class StoreItemRequest extends FormRequest {

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool {

        return true;

    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array {

        return [
            'name'        => ['required', 'string', 'min:2', 'max:65'],
            'description' => 'required|string|min:2|max:85',
            'price'       => 'required|numeric|min:0.1|max:999999999.99|decimal:0,2',
            'status'      => 'required|string'
        ];

    }

}
