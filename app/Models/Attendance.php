<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = ['gym_id', 'user_id', 'course_id', 'checked_in_by', 'check_in_time', 'method'];

    protected function casts(): array
    {
        return [
            'check_in_time' => 'datetime',
        ];
    }

    public function gym(): BelongsTo
    {
        return $this->belongsTo(Gym::class);
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function checkedInBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'checked_in_by');
    }

    public function scopeForCurrentGym(Builder $query): Builder
    {
        if (auth()->check() && ! auth()->user()->isSuperAdmin()) {
            $query->where('gym_id', auth()->user()->gym_id);
        }

        return $query;
    }
}
