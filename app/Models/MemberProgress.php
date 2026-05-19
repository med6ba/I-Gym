<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MemberProgress extends Model
{
    use HasFactory;

    protected $table = 'member_progress';

    protected $fillable = [
        'gym_id',
        'member_id',
        'weight',
        'body_fat',
        'muscle_mass',
        'goal',
        'notes',
        'recorded_at',
    ];

    protected function casts(): array
    {
        return [
            'weight' => 'decimal:2',
            'body_fat' => 'decimal:2',
            'muscle_mass' => 'decimal:2',
            'recorded_at' => 'date',
        ];
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(User::class, 'member_id');
    }

    public function gym(): BelongsTo
    {
        return $this->belongsTo(Gym::class);
    }

    public function scopeForCurrentGym(Builder $query): Builder
    {
        if (auth()->check() && ! auth()->user()->isSuperAdmin()) {
            $query->where('gym_id', auth()->user()->gym_id);
        }

        return $query;
    }
}
