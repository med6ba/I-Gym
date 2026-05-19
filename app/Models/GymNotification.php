<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GymNotification extends Model
{
    use HasFactory;

    protected $table = 'notifications';

    protected $fillable = ['gym_id', 'user_id', 'title', 'message', 'type', 'is_read'];

    protected function casts(): array
    {
        return [
            'is_read' => 'boolean',
        ];
    }

    public function gym(): BelongsTo
    {
        return $this->belongsTo(Gym::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeForCurrentGym(Builder $query): Builder
    {
        if (auth()->check() && ! auth()->user()->isSuperAdmin()) {
            $query->where('gym_id', auth()->user()->gym_id);
        }

        return $query;
    }
}
