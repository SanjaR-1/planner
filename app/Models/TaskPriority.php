<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
class TaskPriority extends Model
{
    protected $fillable = [
        'name',
        'sort_order',
        'is_active'
    ];
    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class, 'priority_id');
    }
}

