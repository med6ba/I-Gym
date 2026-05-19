<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Course extends Model
{
    use HasFactory;

    public const DEFAULT_CATEGORIES = ['Crossfit', 'Yoga', 'Cardio', 'Strength', 'Boxing', 'Pilates'];

    protected $fillable = [
        'gym_id',
        'coach_id',
        'title',
        'category',
        'description',
        'starts_at',
        'ends_at',
        'max_capacity',
        'room',
        'status',
    ];

    protected $appends = ['occupancy_rate', 'is_full', 'smart_alert'];

    protected function casts(): array
    {
        return [
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
        ];
    }

    public function gym(): BelongsTo
    {
        return $this->belongsTo(Gym::class);
    }

    public function coach(): BelongsTo
    {
        return $this->belongsTo(User::class, 'coach_id');
    }

    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }

    public function activeReservations(): HasMany
    {
        return $this->reservations()->whereIn('status', ['reserved', 'attended']);
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    public function getOccupancyRateAttribute(): int
    {
        if (! $this->max_capacity) {
            return 0;
        }

        $count = $this->active_reservations_count ?? $this->activeReservations()->count();

        return min(100, (int) round(($count / $this->max_capacity) * 100));
    }

    public function getIsFullAttribute(): bool
    {
        $count = $this->active_reservations_count ?? $this->activeReservations()->count();

        return $count >= $this->max_capacity;
    }

    public function getSmartAlertAttribute(): ?string
    {
        if ($this->is_full) {
            return __('messages.class_full');
        }

        if ($this->occupancy_rate >= 80) {
            return __('messages.high_demand');
        }

        return null;
    }

    public function scopeForCurrentGym(Builder $query): Builder
    {
        if (auth()->check() && ! auth()->user()->isSuperAdmin()) {
            $query->where('gym_id', auth()->user()->gym_id);
        }

        return $query;
    }

    public function scopeAssignedTo(Builder $query, User $coach): Builder
    {
        return $query->where('gym_id', $coach->gym_id)->where('coach_id', $coach->id);
    }

    /**
     * @return array<int, string>
     */
    public static function categoryOptions(?int $gymId = null): array
    {
        $storedCategories = static::query()
            ->when($gymId, fn (Builder $query) => $query->where('gym_id', $gymId))
            ->whereNotNull('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category')
            ->all();

        return collect($storedCategories)
            ->merge(self::DEFAULT_CATEGORIES)
            ->unique()
            ->values()
            ->all();
    }
}
