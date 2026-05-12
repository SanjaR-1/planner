<?php
namespace App\Services;
use App\Exceptions\BusinessException;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class ProjectService
{
    public function paginate(Request $request):LengthAwarePaginator
    {
        $allowedSorts = ['asc', 'desc'];
        $sort = in_array($request->sort, $allowedSorts) ? $request->sort : null;
        $projects = Project::query()
            ->where('is_active', 1)
            ->when(filled($request->search), function($q) use ($request){
                $q->where('name', 'like', '%'.$request->search.'%');
            })
            ->when(filled($request->created_by), function ($query) use ($request) {
                $query->where('created_by', $request->created_by);
            })
            ->when(filled($request->developer_id), function ($query) use ($request) {
                $query->whereHas('developers', function ($query) use ($request) {
                    $query->where('users.id', $request->developer_id);
                });
            })
            ->when($sort, function ($q) use ($sort) {
                $sort === 'asc'
                    ? $q->orderBy('created_at')
                    : $q->orderByDesc('created_at');
            }, function ($q) {
                $q->latest();
            })
            ->with([
                'creator:id,name',
                'developers:id,name'
            ])
            ->paginate((int) $request->get('per_page', 10));
        return $projects;
    }
    public function show(Project $project): Project{
        if($project->is_active == '0'){
            throw new BusinessException();
        }
        return $project->load([
            'creator:id,name',
            'developers:id,name'
        ]);
    }
    public function store(array $data, User $user): Project
    {
        $data['created_by'] = $user->id;
        $project = Project::create($data);
        return $project->load([
            'creator:id,name',
            'developers:id,name'
        ]);
    }
    public function update(Project $project, array $data): Project
    {
        if($project->is_active == '0'){
            throw new BusinessException();
        }
        $project->update($data);
        return $project->refresh()->load([
            'creator:id,name',
            'developers:id,name'
        ]);
    }
    public function delete(Project $project): void
    {
        if( $project->is_active == '0'){
            throw new BusinessException();
        }
        $project->is_active = '0';
        $project->save();
    }
    public function attachUsers(Project $project, array $userIds): Project
    {
        if($project->is_active == '0'){
            throw new BusinessException();
        }
        $project->developers()->syncWithoutDetaching($userIds);
        return $project->load('developers:id,name');
    }
    public function detachUser(Project $project, int $userId): Project
    {
        if($project->is_active == '0'){
            throw new BusinessException();
        }
        $project->developers()->detach($userId);
        return $project->load('developers:id,name');
    }
}
