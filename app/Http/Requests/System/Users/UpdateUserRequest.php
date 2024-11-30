<?php

namespace App\Http\Requests\System\Users;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateUserRequest extends FormRequest {

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
            "id"                        => "required|integer",
            "identity_document_type_id" => "required|integer",
            "document_number"           => "required|string|max:20",
            "name"                      => "required|string|max:100",
            "email"                     => "required|email|unique:users,email,$this->id,id",
            "password"                  => "nullable|max:40",
            "status"                    => "required|string"
        ];

    }

    protected function failedValidation(Validator $validator) {

        $errors = $validator->errors()->toArray();

        throw new HttpResponseException(response()->json(["errors" => $errors, "message" => "Error al validar."], 422));

    }

}
