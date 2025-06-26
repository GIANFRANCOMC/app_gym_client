<?php

namespace App\Http\Requests\System\Companies;

use App\Helpers\System\Utilities;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateCompanyRequest extends FormRequest {

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
            "identity_document_type_id" => "required|integer",
            "document_number"           => "required|string|max:20",
            "legal_name"                => "required|string|max:100",
            "commercial_name"           => "required|string|max:100",
            "tagline"                   => "nullable|string|max:100",
            "description"               => "nullable|string|max:500",
            "address"                   => "nullable|string|max:100",
            "telephone"                 => "nullable|string|max:50",
            "email"                     => "nullable|email",
            "status"                    => "required|string",
            "logotype"                  => "nullable|file|image|mimes:jpeg,png,jpg|max:10240",
            "combinationmark"           => "nullable|file|image|mimes:jpeg,png,jpg|max:10240",
            "logomark"                  => "nullable|file|image|mimes:jpeg,png,jpg|max:10240",
            "login_image"               => "nullable|file|image|mimes:jpeg,png,jpg|max:10240"
        ];

    }

    protected function failedValidation(Validator $validator) {

        $errors = $validator->errors()->toArray();

        throw new HttpResponseException(response()->json(["errors" => $errors, "message" => Utilities::$messages["422"]], 422));

    }

}
