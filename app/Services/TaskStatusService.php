<?php

namespace App\Services;

use App\Models\Project;
use App\Models\TaskStatus;

class TaskStatusService
{
    public function listByProject(Project $project)
    {
        return $project->statuses()
            ->orderBy('sort_order')
            ->get();
    }
    public function store(Project $project, array $data): TaskStatus
    {
        $data['project_id'] = $project->id;

        return TaskStatus::create($data)->load('project:id,name');
    }
    public function update(TaskStatus $status, array $data): TaskStatus
    {
        $status->update($data);

        return $status->refresh()->load('project:id,name');
    }
    public function delete(TaskStatus $status): bool
    {
        return $status->delete();
    }
}
