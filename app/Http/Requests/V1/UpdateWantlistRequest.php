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
     *     @OA\Schema(
     *     schema="UpdateWantlistRequest",
     *     required={"price", "item", "status", "boughtDate"},
     *     @OA\Property(property="price", type="integer", example=100),
     *     @OA\Property(property="item", type="string", example="itemName"),
     *     @OA\Property(property="status", type="string", enum={"W", "B"}, example="W"),
     *     @OA\Property(property="boughtDate", type="[date_format:Y-m-d H:i:s|nullable]", example=null),
     * )
     * 
     * @OA\Schema(
     *     schema="PatchWantlistRequest",
     *     @OA\Property(property="price", type="integer", example=100),
     *     @OA\Property(property="item", type="string", example="itemName"),
     *     @OA\Property(property="status", type="string", enum={"W", "B"}, example="W"),
     *     @OA\Property(property="boughtDate", type="[date_format:Y-m-d H:i:s|nullable]", example=null),
     * )
     */
    public function rules(): array
    {
        $method = $this->method();

        if ($this->method() == "PUT") {
            return [
                "price" => ["required", "integer"],
                "item" => ["required","string"],
                "status" => ["required",Rule::in(["W","B"])],
                "boughtDate" => ["date_format:Y-m-d H:i:s","nullable"],
            ];
        } else {
            return [
                "price" => ["sometimes","required", "integer"],
                "item" => ["sometimes","required","string"],
                "status" => ["sometimes","required",Rule::in(["W","B"])],
                "boughtDate" => ["sometimes","date_format:Y-m-d H:i:s","nullable"],
            ];
        }
    }
}
