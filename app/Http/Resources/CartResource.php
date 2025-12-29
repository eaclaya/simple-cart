<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $items = CartItemResource::collection($this->items);

        return [
            'id' => $this->id,
            'items' => $items,
            'items_count' => $this->items->count(),
            'subtotal' => $this->items->sum(fn ($item) => $item->quantity * $item->product->price),
        ];
    }
}
