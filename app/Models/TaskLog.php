<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class TaskLog extends Model
{
    protected $fillable = [
        'task_id',
        'user_id',
        'from_status_id',
        'to_status_id',
        'comment',
    ];
    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }
    public function user():BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function fromStatus():BelongsTo
    {
        return $this->belongsTo(TaskStatus::class, 'from_status_id');
    }
    public function toStatus():BelongsTo
    {
        return $this->belongsTo(TaskStatus::class, 'to_status_id');
    }
}
