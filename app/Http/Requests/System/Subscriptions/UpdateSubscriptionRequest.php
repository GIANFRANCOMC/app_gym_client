<?php

namespace App\Http\Requests\System\Subscriptions;

use App\Helpers\System\Utilities;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateSubscriptionRequest extends FormRequest {

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

        $round    = Utilities::$inputs["round"];
        $minValue = Utilities::$inputs["minValue"];
        $maxValue = Utilities::$inputs["maxValue"];

        return [
            "internal_code"  => "required|string|max:100",
            "name"           => "required|string|max:100",
            "description"    => "nullable|string|max:300",
            "price"          => "required|numeric|min:$minValue|max:$maxValue|decimal:0,$round",
            "currency_id"    => "required|integer",
            "duration_type"  => "required|string",
            "duration_value" => "required|integer|min:$minValue|max:$maxValue|decimal:0",
            "status"         => "required|string"
        ];

    }

    protected function failedValidation(Validator $validator) {

        $errors = $validator->errors()->toArray();

        throw new HttpResponseException(response()->json(["errors" => $errors, "message" => Utilities::$messages["422"]], 422));

    }

}
