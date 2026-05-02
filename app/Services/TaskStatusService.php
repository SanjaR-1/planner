<?php

namespace App\Services;

use App\Models\TaskStatus;
use Illuminate\Support\Collection;

class TaskStatusService
{
    public function list(): Collection
    {
        return TaskStatus::orderBy('sort_order','asc')->get();
    }
    public function store( array $data): TaskStatus
    {
        if (!isset($data['sort_order'])) {
            $data['sort_order'] = TaskStatus::max('sort_order') + 1;
        }
        return TaskStatus::create($data);
    }
    public function update(TaskStatus $status, array $data): TaskStatus
    {
        $status->update($data);
        return $status->refresh();
    }
    public function delete(TaskStatus $status): bool
    {
        return $status->delete();
    }
}
