<?php

namespace App\Http\Requests\System\BookComplaints;

use App\Helpers\System\Utilities;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateBookComplaintRequest extends FormRequest {

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
            "admin_response" => "required|string|max:900",
            "status"         => "required|string"
        ];

    }

    protected function failedValidation(Validator $validator) {

        $errors = $validator->errors()->toArray();

        throw new HttpResponseException(response()->json(["errors" => $errors, "message" => Utilities::$messages["422"]], 422));

    }

}
