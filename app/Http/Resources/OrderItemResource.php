<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderItemResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'qty' => $this->qty,
            'price' => $this->product->price,
            'subtotal' => round($this->qty * $this->product->price, 2),
            'product' => $this->product->name,
            'category' => $this->product->category->name
        ];
    }
}
