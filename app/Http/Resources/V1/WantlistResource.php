<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WantlistResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "customerId"=> $this->customer_id,
            "price" => $this->price,
            "item" => $this->item,
            "status" => $this->status,
            "boughtDate" => $this->bought_date
        ];;
    }
}
