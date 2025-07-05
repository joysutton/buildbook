<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'title',
        'description',
        'share',
        'due_date',
        'completion_date',
    ];

    protected $casts = [
        'share' => 'boolean',
        'due_date' => 'datetime',
        'completion_date' => 'datetime',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function notes(): MorphMany
    {
        return $this->morphMany(\App\Models\Note::class, 'noteable');
    }
}
