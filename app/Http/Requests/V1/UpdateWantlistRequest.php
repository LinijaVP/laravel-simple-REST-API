<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateWantlistRequest extends FormRequest
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
        $method = $this->method();

        if ($this->method() == "PUT") {
            return [
                "customerId" => ["required","integer"],
                "price" => ["required", "integer"],
                "item" => ["required","string"],
                "status" => ["required",Rule::in(["W","B"])],
                "boughtDate" => ["date_format:Y-m-d H:i:s","nullable"],
            ];
        } else {
            return [
                "customerId" => ["sometimes","required","integer"],
                "price" => ["sometimes","required", "integer"],
                "item" => ["sometimes","required","string"],
                "status" => ["sometimes","required",Rule::in(["W","B"])],
                "boughtDate" => ["sometimes","date_format:Y-m-d H:i:s","nullable"],
            ];
        }
    }
}
