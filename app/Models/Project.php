<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
class Project extends Model
{
    protected $fillable = [
        'created_by',
        'name',
        'description',
    ];
    public function creator():BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function developers():BelongsToMany
    {
        return $this->belongsToMany(User::class, 'project_user');
    }
    public function statuses():HasMany
    {
        return $this->hasMany(TaskStatus::class);
    }
    public function tasks():HasMany
    {
        return $this->hasMany(Task::class);
    }
}
