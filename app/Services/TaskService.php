<?php

namespace App\Services;

use App\Models\Task;
use App\Models\Project;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class TaskService
{
    public function paginate(int $perPage = 10)
    {
        return Task::with([
            'project:id,name',
            'status:id,name',
            'priority:id,name',
            'developer:id,name',
            'creator:id,name',
        ])->latest()->paginate($perPage);
    }
    public function store(array $data, User $user, Project $project): Task
    {
        if (!empty($data['assigned_to'])) {
            $this->developerProjectgaBiriktirilganmi(
                $project->id,
                $data['assigned_to']
            );
        }
        $data['project_id'] = $project->id;
        $data['created_by'] = $user->id;
        $task = Task::create($data);
        return $task->load([
            'project:id,name',
            'creator:id,name',
            'developer:id,name',
            'status:id,name',
            'priority:id,name',
        ]);
    }
    public function update(Task $task, array $data): Task
    {
        $projectId = $data['project_id'] ?? $task->project_id;

        if (!empty($data['assigned_to'])) {
            $this->developerProjectgaBiriktirilganmi(
                $projectId,
                $data['assigned_to']
            );
        }
        $task->update($data);
        return $task->refresh()->load([
            'project:id,name',
            'creator:id,name',
            'developer:id,name',
            'status:id,name',
            'priority:id,name',
        ]);
    }

    public function delete(Task $task): bool
    {
        return $task->delete();
    }
    private function developerProjectgaBiriktirilganmi(int $projectId, int $developerId): void
    {
        $exists = DB::table('project_user')
            ->where('project_id', $projectId)
            ->where('user_id', $developerId)
            ->exists();
        if (! $exists) {
            throw ValidationException::withMessages([
                'assigned_to' => ['Bu developer ushbu projectga biriktirilmagan.'],
            ]);
        }
    }
}
