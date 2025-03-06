<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Team extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public function taskStatuses(): HasMany
    {
        return $this->hasMany(TaskStatus::class);
    }

    public function tags(): hasMany
    {
        return $this->hasMany(Tag::class);
    }

    public function tasks(): hasMany
    {
        return $this->hasMany(Task::class);
    }

    public function credentials(): hasMany
    {
        return $this->hasMany(Credential::class);
    }
}
