<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'          => 'required|string|max:255',
            'category_id'   => 'required|exists:category,id',
            'description'   => 'nullable|string|max:255',
            'price'         => 'required|numeric|min:0',
        ];
    }
}
