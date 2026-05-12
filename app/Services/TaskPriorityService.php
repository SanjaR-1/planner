<?php

namespace App\Services;

use App\Exceptions\BusinessException;
use App\Models\TaskPriority;
use Illuminate\Support\Collection;

class TaskPriorityService
{
    public function list(): Collection{
        return TaskPriority::orderBy('sort_order', 'asc')
            ->where('is_active','=','1')
            ->get();
    }
    public function show(TaskPriority $taskPriority): TaskPriority{
        if($taskPriority->is_active == '0'){
            throw new BusinessException();
        }
        return $taskPriority;
    }
    public function store(array $priority): TaskPriority{
        $priority['sort_order'] = $priority['sort_order'] ?? TaskPriority::max('sort_order') + 1;
        return TaskPriority::create($priority);
    }
    public function update(TaskPriority $priority,array $data): TaskPriority{
        if($priority->is_active == '0'){
            throw new BusinessException();
        }
        $priority->update($data);
        return $priority->refresh();
    }
    public function delete(TaskPriority $priority):void{
        if($priority->is_active == '0'){
            throw new BusinessException();
        }
        $priority->is_active = '0';
        $priority->save();
    }
}
