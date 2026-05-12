<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTaskStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    public function rules(): array
    {
        return [
            'name' => [
                'sometimes',
                'string',
                'max:255',
                Rule::unique('task_statuses', 'name')
                    ->ignore($this->route('status')),
            ],
            'sort_order' => 'sometimes|nullable|integer|min:0',
        ];
    }
}
