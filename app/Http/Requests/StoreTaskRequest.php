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
            'project_id' => 'required|exists:projects,id',
            'assigned_to' => 'required|exists:users,id',
            'status_id' => 'required|exists:task_statuses,id',
            'title' => 'required|string|max:255',
            'body' => 'nullable|string',
            'deadline' => 'nullable|date',
        ];
    }
}
