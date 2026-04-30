<?php

namespace App\Services;

use App\Models\Task;
use App\Models\User;
use App\Models\TaskStatus;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class TaskService
{
    public function paginate(int $perPage = 10)
    {
        return Task::with([
            'project:id,name',
            'creator:id,name',
            'developer:id,name',
            'status:id,name',
        ])
            ->latest()
            ->paginate($perPage);
    }

    public function store(array $data, User $user): Task
    {
        $this->ensureDeveloperBelongsToProject(
            $data['project_id'],
            $data['assigned_to']
        );

        $this->ensureStatusBelongsToProject(
            $data['project_id'],
            $data['status_id']
        );

        $data['created_by'] = $user->id;

        $task = Task::create($data);

        return $task->load([
            'project:id,name',
            'creator:id,name',
            'developer:id,name',
            'status:id,name',
        ]);
    }

    public function update(Task $task, array $data): Task
    {
        $projectId = $data['project_id'] ?? $task->project_id;

        if (isset($data['assigned_to'])) {
            $this->ensureDeveloperBelongsToProject(
                $projectId,
                $data['assigned_to']
            );
        }

        if (isset($data['status_id'])) {
            $this->ensureStatusBelongsToProject(
                $projectId,
                $data['status_id']
            );
        }

        $task->update($data);

        return $task->refresh()->load([
            'project:id,name',
            'creator:id,name',
            'developer:id,name',
            'status:id,name',
        ]);
    }

    public function delete(Task $task): bool
    {
        return $task->delete();
    }

    public function updateStatus(Task $task, array $data, User $user): Task
    {
        $this->ensureStatusBelongsToProject(
            $task->project_id,
            $data['status_id']
        );

        $oldStatusId = $task->status_id;

        DB::transaction(function () use ($task, $data, $user, $oldStatusId) {
            $task->update([
                'status_id' => $data['status_id'],
            ]);

            $task->logs()->create([
                'user_id' => $user->id,
                'from_status_id' => $oldStatusId,
                'to_status_id' => $data['status_id'],
                'comment' => $data['comment'] ?? null,
            ]);
        });

        return $task->refresh()->load([
            'project:id,name',
            'creator:id,name',
            'developer:id,name',
            'status:id,name',
            'logs',
        ]);
    }

    private function ensureDeveloperBelongsToProject(int $projectId, int $developerId): void
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

    private function ensureStatusBelongsToProject(int $projectId, int $statusId): void
    {
        $exists = TaskStatus::where('id', $statusId)
            ->where('project_id', $projectId)
            ->exists();

        if (! $exists) {
            throw ValidationException::withMessages([
                'status_id' => ['Bu status ushbu projectga tegishli emas.'],
            ]);
        }
    }
}
