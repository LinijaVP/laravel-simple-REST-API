<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     * @OA\Schema(
     *     schema="CustomerResource",
     *     @OA\Property(property="name", type="string", example="Name"),
     *     @OA\Property(property="type", type="string", enum={"S", "G"}, example="S"),
     *     @OA\Property(property="email", type="email", example="vp@gmail.com"),
     *     @OA\Property(property="city", type="string", example="Portland"),
     *     @OA\Property(property="country", type="string", example="Oregon"),
     *     @OA\Property(property="budget", type="integer", example=1000)
     * )
     */

     
    public function toArray(Request $request): array
    {
        return [
            "id"=> $this->id,
            "name"=> $this->name,
            "type"=> $this->type,
            "email"=> $this->email,
            "city" => $this->city,
            "country" => $this->country,
            "budget" => $this->budget,
            "wantlist" => WantlistResource::collection($this->whenLoaded("wantlist")),
            
        ];
    }
}
