<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'gym_id',
        'name',
        'email',
        'password',
        'role',
        'phone',
        'avatar',
        'status',
        'language',
        'theme',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function gym(): BelongsTo
    {
        return $this->belongsTo(Gym::class);
    }

    public function coachedCourses(): HasMany
    {
        return $this->hasMany(Course::class, 'coach_id');
    }

    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    public function activeSubscription(): HasOne
    {
        return $this->hasOne(Subscription::class)->where('status', 'active')->latestOfMany('ends_at');
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    public function trainingPlansAsCoach(): HasMany
    {
        return $this->hasMany(TrainingPlan::class, 'coach_id');
    }

    public function trainingPlansAsMember(): HasMany
    {
        return $this->hasMany(TrainingPlan::class, 'member_id');
    }

    public function progressEntries(): HasMany
    {
        return $this->hasMany(MemberProgress::class, 'member_id');
    }

    public function scopeForCurrentGym(Builder $query): Builder
    {
        if (auth()->check() && ! auth()->user()->isSuperAdmin()) {
            $query->where('gym_id', auth()->user()->gym_id);
        }

        return $query;
    }

    public function scopeRole(Builder $query, string $role): Builder
    {
        return $query->where('role', $role);
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin';
    }

    public function isGymAdmin(): bool
    {
        return $this->role === 'gym_admin';
    }

    public function isCoach(): bool
    {
        return $this->role === 'coach';
    }

    public function isMember(): bool
    {
        return $this->role === 'member';
    }

    public function dashboardRoute(): string
    {
        return match ($this->role) {
            'super_admin' => 'super.dashboard',
            'gym_admin' => 'admin.dashboard',
            'coach' => 'coach.dashboard',
            default => 'member.dashboard',
        };
    }
}
