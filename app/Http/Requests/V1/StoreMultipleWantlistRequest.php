<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
class StoreMultipleWantlistRequest extends FormRequest
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
            "*.customerId" => ["required","integer"],
            "*.price" => ["required", "integer"],
            "*.item" => ["required","string"],
            "*.status" => ["required",Rule::in(["W","B"])],
            "*.boughtDate" => ["date_format:Y-m-d H:i:s","nullable"],
        ];
    }

    protected function prepareForValidation() {
        $data = [];
        foreach ($this->toArray() as $object) {
            $object["customer_id"] = $object["customerId"] ?? null;
            $object["bought_date"] = $object["boughtDate"] ?? null;
            $data[] = $object;
        }

        $this->merge($data);
    }
}
