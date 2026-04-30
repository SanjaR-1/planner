<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
class Task extends Model
{
    protected $fillable = [
        'project_id',
        'created_by',
        'assigned_to',
        'status_id',
        'body',
        'title',
        'deadline',
    ];
    protected $casts = [
        'deadline' => 'date',
    ];
    public function project():BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function developer():BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function status():BelongsTo
    {
        return $this->belongsTo(TaskStatus::class, 'status_id');
    }
    public function comments():HasMany
    {
        return $this->hasMany(TaskComment::class);
    }
    public function logs():HasMany
    {
        return $this->hasMany(TaskLog::class)->latest();
    }
    public function attachments():HasMany
    {
        return $this->hasMany(TaskAttachment::class);
    }
}
