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
            'priority_id' => 'sometimes|exists:task_priorities,id',
            'title' => 'sometimes|string|max:255',
            'body' => 'sometimes|string',
            'deadline' => 'sometimes|date|after_or_equal:now',
        ];
    }
}
