<?php

namespace App\Services;

use App\Models\Project;
use App\Models\TaskPriority;
use App\Models\TaskStatus;
use Illuminate\Support\Collection;

class TaskPriorityService
{
    public function list(): Collection{
        return TaskPriority::orderBy('sort_order', 'asc')->get();
    }
    public function store(array $priority): TaskPriority{
        if(!isset($priority['sort_order'])){
            $priority['sort_order'] = TaskPriority::max('sort_order') + 1;
        }
        return TaskPriority::create($priority);
    }
    public function update(TaskPriority $priority,array $data): TaskPriority{
        $priority->update($data);
        return $priority;
    }
    public function delete(TaskPriority $priority):bool{
        return $priority->delete();
    }
}
