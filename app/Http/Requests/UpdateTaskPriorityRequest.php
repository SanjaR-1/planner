<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTaskPriorityRequest extends FormRequest
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
                Rule::unique('task_priorities', 'name')
                    ->ignore($this->route('priority')),
            ],
            'sort_order' => 'sometimes|nullable|integer|min:0',
        ];
    }
}
