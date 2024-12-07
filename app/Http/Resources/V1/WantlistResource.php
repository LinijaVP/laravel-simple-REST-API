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
     * @OA\Schema(
     *     schema="WantlistResource",
     *     @OA\Property(property="id", type="integer"),
     *     @OA\Property(property="customerId", type="integer"),
     *     @OA\Property(property="price", type="integer", example=10),
     *     @OA\Property(property="item", type="string", example="itemName"),
     *     @OA\Property(property="status", type="string", enum={"W","B"}, example="W"),
     *     @OA\Property(property="boughtDate", type="[date_format:Y-m-d H:i:s|nullable]", example=null)
     * )
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
