<?php

namespace App\Http\Resources;

use App\Http\Resources\UserResource;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'user' => new UserResource($this->whenLoaded('user')),
            'status' => $this->status,
            'orderItems' => OrderItemResource::collection($this->whenLoaded('orderItems')),
            'total_amount' => round($this->orderItems->sum(function ($item) {
                return $item->qty * $item->product->price;
            }), 2),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
