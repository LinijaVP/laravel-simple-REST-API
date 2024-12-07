<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
class StoreCustomerRequest extends FormRequest
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
     * 
     * @OA\Schema(
     *     schema="StoreCustomerRequest",
     *     required={"name", "type", "email", "city", "country", "budget"},
     *     @OA\Property(property="name", type="string", example="Name"),
     *     @OA\Property(property="type", type="string", enum={"S", "G"}, example="S"),
     *     @OA\Property(property="email", type="email", example="vp@gmail.com"),
     *     @OA\Property(property="city", type="string", example="Portland"),
     *     @OA\Property(property="country", type="string", example="Oregon"),
     *     @OA\Property(property="budget", type="integer", example=1000)
     * )
     */
    
    public function rules(): array
    {
        return [
            "name" => ["required","string"],
            "type" => ["required", Rule::in(["S","G"])],
            "email" => ["required","email"],
            "city" => ["required","string"],
            "country" => ["required","string"],
            "budget" => ["required","integer"],
        ];
    }

}
