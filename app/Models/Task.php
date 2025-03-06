<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'due_date',
        'time_estimate',
        'task_status_id',
        'team_id',
    ];

    public function taskStatus(): HasOne
    {
        return $this->hasOne(TaskStatus::class);
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function assignees(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    public function taskTimeTrackings(): hasMany
    {
        return $this->hasMany(TaskTimeTracking::class);
    }

    public function checklists(): hasMany
    {
        return $this->hasMany(Checklist::class);
    }

    protected $casts = [
        'due_date' => 'datetime',
    ];
}
