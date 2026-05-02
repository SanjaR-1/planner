<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'assigned_to' => 'nullable|exists:users,id',
            'status_id' => 'required|exists:task_statuses,id',
            'priority_id' => 'nullable|exists:task_priorities,id',
            'title' => 'required|string|max:255',
            'body' => 'nullable|string',
            'deadline' => 'nullable|date|after_or_equal:today',
        ];
    }
}
