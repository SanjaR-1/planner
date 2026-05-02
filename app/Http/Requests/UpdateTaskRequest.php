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
            'assigned_to' => 'sometimes|nullable|exists:users,id',
            'status_id' => 'sometimes|nullable|exists:task_statuses,id',
            'priority_id' => 'sometimes|nullable|exists:task_priorities,id',
            'title' => 'sometimes|string|max:255',
            'body' => 'sometimes|nullable|string',
            'deadline' => 'sometimes|nullable|date|after_or_equal:today',
        ];
    }
}
