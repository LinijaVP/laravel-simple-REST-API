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
