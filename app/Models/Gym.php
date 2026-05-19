<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Gym extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'email',
        'phone',
        'address',
        'city',
        'status',
        'subscription_plan',
        'subscription_started_at',
        'subscription_ends_at',
        'logo',
    ];

    protected function casts(): array
    {
        return [
            'subscription_started_at' => 'datetime',
            'subscription_ends_at' => 'datetime',
        ];
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function members(): HasMany
    {
        return $this->users()->where('role', 'member');
    }

    public function coaches(): HasMany
    {
        return $this->users()->where('role', 'coach');
    }

    public function courses(): HasMany
    {
        return $this->hasMany(Course::class);
    }

    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    public function activityLogs(): HasMany
    {
        return $this->hasMany(GymActivityLog::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'active');
    }
}
