<?php
namespace App\Services;
use App\Models\Project;
use App\Models\User;
class ProjectService
{
    public function paginate($request)
    {
        $search = $request->search;
        $projects = Project::query()
            ->when($search, function($q) use ($search){
                $q->where('name', 'like', "%{$search}%");
            })
            ->when($request->created_by, function ($query) use ($request) {
                $query->where('created_by', $request->created_by);
            })
            ->when($request->developer_id, function ($query) use ($request) {
                $query->whereHas('developers', function ($query) use ($request) {
                    $query->where('users.id', $request->developer_id);
                });
            })
            ->when($request->sort === 'created_at_asc', fn ($q) => $q->orderBy('created_at'))
            ->when($request->sort === 'created_at_desc', fn ($q) => $q->orderByDesc('created_at'))
            ->with([
                'creator:id,name',
                'developers:id,name'
            ])
            ->latest()
            ->paginate((int) min($request->get('per_page', 10),70) );
        return $projects;
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
        $project->update($data);

        return $project->refresh()->load([
            'creator:id,name',
            'developers:id,name'
        ]);
    }
    public function delete(Project $project): bool
    {
        return $project->delete();
    }
    public function attachUsers(Project $project, array $userIds): Project
    {
        $project->developers()->syncWithoutDetaching($userIds);

        return $project->load('developers:id,name');
    }
    public function detachUser(Project $project, int $userId): Project
    {
        $project->developers()->detach($userId);

        return $project->load('developers:id,name');
    }
}
