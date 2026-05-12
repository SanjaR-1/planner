<?php

namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class UserRegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'phone' => 'required|unique:users,phone|regex:/^998[0-9]{9}$/',
            'password' => 'required|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9]).{8,}$/',
            'role_id' => 'required|exists:roles,id'
        ];
    }
}
