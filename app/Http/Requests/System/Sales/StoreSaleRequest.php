<?php

namespace App\Http\Requests\System\Sales;

use App\Helpers\System\Utilities;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreSaleRequest extends FormRequest {

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
        $maxValue = Utilities::$inputs["maxValue"];

        return [
            // Header
            "branch_id"   => "required|integer",
            "serie_id"    => "required|integer",
            "holder_id"   => "required|integer",
            "currency_id" => "required|integer",
            "issue_date"  => "required|date",
            "observation" => "nullable|string|max:300",
            // Details
            "details" => "required|array",
            "details.*.item_id" => "required|integer",
            "details.*.type" => "required|string|max:255",
            "details.*.currency_id" => "required|integer",
            "details.*.name" => "required|string|max:255",
            "details.*.quantity" => "required|numeric|min:0.1|max:$maxValue|decimal:0,$round",
            "details.*.price" => "required|numeric|min:0.1|max:$maxValue|decimal:0,$round",
            "details.*.observation" => "nullable|string|max:300"
        ];

    }

    protected function failedValidation(Validator $validator) {

        $errors = $validator->errors()->toArray();

        // Props rename
        if(isset($errors["branch_id"])) {

            $errors["branch"] = $errors["branch_id"];
            unset($errors["branch_id"]);

        }

        if(isset($errors["serie_id"])) {

            $errors["serie"] = $errors["serie_id"];
            unset($errors["serie_id"]);

        }

        if(isset($errors["holder_id"])) {

            $errors["holder"] = $errors["holder_id"];
            unset($errors["holder_id"]);

        }

        if(isset($errors["currency_id"])) {

            $errors["currency"] = $errors["currency_id"];
            unset($errors["currency_id"]);

        }

        throw new HttpResponseException(response()->json(["errors" => $errors, "message" => Utilities::$messages["422"]], 422));

    }

}
