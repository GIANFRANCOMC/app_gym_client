<?php

namespace App\Http\Requests\System\Products;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreProductRequest extends FormRequest {

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
            "internal_code" => "required|string|max:100",
            "name"          => "required|string|max:100",
            "description"   => "nullable|string|max:300",
            "price"         => "required|numeric|min:0.1|max:999999999.99|decimal:0,2",
            "currency_id"   => "required|integer",
            "status"        => "required|string"
        ];

    }

    protected function failedValidation(Validator $validator) {

        $errors = $validator->errors()->toArray();

        throw new HttpResponseException(response()->json(["errors" => $errors, "message" => "Error al validar."], 422));

    }

}
