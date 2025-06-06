<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'firstname'   => 'required|string|max:255',
            'surname'     => 'required|string|max:255',
            'patronymic'  => 'nullable|string|max:255',
            'email'       => 'required|email|unique:users,email',
            'name'        => 'required|string|max:255',
            'password'    => 'required|string|min:8',
        ];
    }
}
