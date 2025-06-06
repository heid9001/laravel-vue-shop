<?php

namespace App\Http\Requests\Order;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'orderItems' => 'required|array|min:1',
            'orderItems.*.product_id' => 'required|exists:products,id',
            'orderItems.*.qty' => 'required|integer|min:1',
        ];
    }
} 