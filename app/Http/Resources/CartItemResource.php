<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $lineTotal = $this->quantity * $this->product->price;

        return [
            'id' => $this->id,
            'quantity' => $this->quantity,
            'product' => new ProductResource($this->product),
            'line_total' => $lineTotal,
        ];
    }
}
