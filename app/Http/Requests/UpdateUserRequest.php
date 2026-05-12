<?php

namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    public function rules(): array
    {
        return [
            'name' => 'sometimes|string|max:255',
            'phone' => [
                'sometimes',
                'regex:/^998[0-9]{9}$/',
                Rule::unique('users', 'phone')->ignore($this->route('user')),
            ],
            'password' => 'nullable|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9]).{8,}$/',
            'role_id' => 'sometimes|exists:roles,id'
        ];
    }
}
