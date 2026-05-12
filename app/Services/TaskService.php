<?php
namespace App\Services;
use App\Exceptions\BusinessException;
use App\Models\Task;
use App\Models\Project;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
class TaskService
{
    public function paginate(Request $request):LengthAwarePaginator
    {
        $allowedSorts = ['asc', 'desc'];
        $sort = in_array($request->sort, $allowedSorts) ? $request->sort : null;
        $tasks = Task::query()
            ->where('is_active','=','1')
            #task title bo'yicha poisk
            ->when(filled($request->search), function ($query) use ($request) {
                $query->where('title', 'like', '%'.$request->search.'%');
            })
            #qaysi vaqt oralig'ida qilingan
            ->when($request->start_date && $request->end_date,
                function ($query) use ($request) {
                    $query->whereBetween('created_at', [
                        Carbon::parse($request->start_date)->startOfDay(),
                        Carbon::parse($request->end_date)->endOfDay()
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
            ->when($sort,function ($q) use ($sort){
                if($sort == 'asc'){
                    $q->orderBy('created_at');
                }else{
                    $q->orderByDesc('created_at');
                }
            },function ($q){
                $q->latest();
            })
            ->with([
                'project:id,name',
                'status:id,name',
                'priority:id,name',
                'developer:id,name',
                'creator:id,name',
            ])
            ->paginate((int) $request->get('per_page', 10));
        return $tasks;
    }
    public function show(Project $project, Task $task):Task{
        if ($task->project_id !== $project->id) {
            throw new BusinessException();
        }
        return $task->load([
            'project:id,name',
            'creator:id,name',
            'developer:id,name',
            'status:id,name',
            'priority:id,name',
        ]);
    }
    public function store(array $data, User $user, Project $project): Task
    {
        if (!empty($data['assigned_to'])) {
            $this->attachedDeveloperCheck(
                $project,
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
        if($task->is_active == '0'){
            throw new BusinessException();
        }
        if (!empty($data['assigned_to'])) {
            $project = Project::findOrFail($data['project_id'] ?? $task->project_id);
            $this->attachedDeveloperCheck(
                $project,
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
    public function delete(Task $task): void
    {
        if($task -> is_active == '0'){
            throw new BusinessException();
        }
        $task->is_active = '0';
        $task->save();
    }
    private function attachedDeveloperCheck(Project $project, int $developerId): void
    {
        $exists = $project->developers()
            ->where('users.id', $developerId)
            ->exists();
        if (! $exists) {
            throw new BusinessException();
        }
    }
}
