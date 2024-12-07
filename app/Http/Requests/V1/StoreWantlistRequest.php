<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
class StoreWantlistRequest extends FormRequest
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
     * @OA\Schema(
     *     schema="StoreWantlistRequest",
     *     required={"customerId", "price", "item", "status", "boughtDate"},
     *     @OA\Property(property="customerId", type="integer", example=105),
     *     @OA\Property(property="price", type="integer", example=100),
     *     @OA\Property(property="item", type="string", example="itemName"),
     *     @OA\Property(property="status", type="string", enum={"W", "B"}, example="W"),
     *     @OA\Property(property="boughtDate", type="[date_format:Y-m-d H:i:s|nullable]", example=null),
     * )
     */
    public function rules(): array
    {
        return [
            "customerId" => ["required","integer"],
            "price" => ["required", "integer"],
            "item" => ["required","string"],
            "status" => ["required",Rule::in(["W","B"])],
            "boughtDate" => ["date_format:Y-m-d H:i:s","nullable"],
        ];
    }

    protected function prepareForValidation() {
        $object = $this->toArray();
        $data = [];
        $data["customer_id"] = $object["customerId"] ?? null;
        $data["bought_date"] = $object["boughtDate"] ?? null;
        $this->merge($data);
    }
}
