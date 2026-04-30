<?php
namespace App\Services;
use App\Models\Project;
use App\Models\User;
class ProjectService
{
    public function paginate(int $perPage = 10)
    {
        return Project::with([
                'creator:id,name',
                'developers:id,name'
            ])
            ->latest()
            ->paginate($perPage);
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
