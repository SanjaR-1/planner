<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'assigned_to' => 'sometimes|exists:users,id',
            'status_id' => 'sometimes|exists:task_statuses,id',
            'title' => 'sometimes|string|max:255',
            'body' => 'nullable|string',
            'deadline' => 'nullable|date',
        ];
    }
}
