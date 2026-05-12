<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
class TaskStatus extends Model
{
    protected $fillable = [
        'name',
        'sort_order',
        'is_active'
    ];
    public function tasks():HasMany
    {
        return $this->hasMany(Task::class, 'status_id');
    }
}
