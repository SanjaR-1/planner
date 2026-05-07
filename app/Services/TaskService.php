<?php

namespace App\Services;

use App\Models\Task;
use App\Models\Project;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
class TaskService
{
    public function paginate($request)
    {
        $search = $request->search;
        $tasks = Task::query()
            #task title bo'yicha poisk
            ->when(filled($search), function ($query) use ($search) {
                $query->where('title', 'like', "%{$search}%");
            })
            #qaysi vaqt oralig'ida qilingan
            ->when($request->start_date && $request->end_date,
                function ($query) use ($request) {
                    $query->whereBetween('created_at', [
                        $request->start_date,
                        $request->end_date
                    ]);
                }
            )
            #assign qilingan developer bo'yicha filtr
            ->when(filled($request->assigned_to),
                fn ($query) => $query
                    ->where('assigned_to', $request->assigned_to)
            )
            #Project bo'yicha
            ->when(filled($request->project_id),
                fn ($query) => $query
                    ->where('project_id', $request->project_id)
            )
            #prioritet bo'yicha
            ->when(filled($request->priority_id),
                fn ($query) => $query
                    ->where('priority_id', $request->priority_id)
            )
            #status bo'yicha
            ->when(filled($request->status_id),
                fn ($query) => $query
                    ->where('status_id', $request->status_id)
            )
            #creator bo'yicha
            ->when(filled($request->created_by),
                fn ($query) => $query
                ->where('created_by', $request->created_by)
            )
            ->when($request->sort === 'created_at_asc', fn ($q) => $q->orderBy('created_at'))
            ->when($request->sort === 'created_at_desc', fn ($q) => $q->orderByDesc('created_at'))
            ->with([
                'project:id,name',
                'status:id,name',
                'priority:id,name',
                'developer:id,name',
                'creator:id,name',
            ])->paginate((int) min($request -> get('per_page',10),70));
        return $tasks;
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
