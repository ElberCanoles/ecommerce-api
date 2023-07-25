<?php

namespace App\Http\Requests\User;

use Illuminate\Validation\Rules\Password;
use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'regex:/^[a-zA-Z\sáÁéÉíÍóÓúÚüÜñÑ]+$/u', 'max:40'],
            'last_name' => ['required', 'string', 'regex:/^[a-zA-Z\sáÁéÉíÍóÓúÚüÜñÑ]+$/u', 'max:40'],
            'email' => ['required', 'string', 'email', 'max:80', 'unique:users'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ];
    }
}
