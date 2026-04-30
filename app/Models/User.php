<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    protected $fillable = [
        'role_id',
        'name',
        'phone',
        'password',
    ];
    protected $hidden = [
        'password',
        'remember_token',
    ];
    public function role()
    {
        return $this->belongsTo(Role::class);
    }
    public function createdProjects()
    {
        return $this->hasMany(Project::class, 'created_by');
    }
    public function projects()
    {
        return $this->belongsToMany(Project::class, 'project_user');
    }
    public function createdTasks()
    {
        return $this->hasMany(Task::class, 'created_by');
    }
    public function assignedTasks()
    {
        return $this->hasMany(Task::class, 'assigned_to');
    }
    public function taskComments()
    {
        return $this->hasMany(TaskComment::class);
    }
    public function taskLogs()
    {
        return $this->hasMany(TaskLog::class);
    }
    public function hasPermission(string $permission): bool
    {
        return $this->role()
            ->whereHas('permissions', function ($query) use ($permission) {
                $query->where('name', $permission);
            })
            ->exists();
    }
}
