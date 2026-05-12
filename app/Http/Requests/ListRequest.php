<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ListRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    public function rules(): array
    {
        return [
            'per_page' => 'sometimes|integer|min:1|max:70',
            'search'   => 'sometimes|string|max:255',
            'sort'     => 'sometimes|string|in:asc,desc',
        ];
    }
}
