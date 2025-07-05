<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Material extends Model
{
    /** @use HasFactory<\Database\Factories\MaterialFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'amount',
        'est_cost',
        'actual_cost',
        'source',
        'acquired',
        'share',
        'project_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'acquired' => 'boolean',
        'share' => 'boolean',
        'est_cost' => 'integer',
        'actual_cost' => 'integer',
    ];

    /**
     * Get the project that owns the material.
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function notes(): MorphMany
    {
        return $this->morphMany(\App\Models\Note::class, 'noteable');
    }
}
