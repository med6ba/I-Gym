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
        'currency',
        'age',
        'height_cm',
        'weight_kg',
        'gender',
        'fitness_goal',
        'bio',
        'bracelet_uid',
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
            'height_cm' => 'decimal:2',
            'weight_kg' => 'decimal:2',
        ];
    }

    public function avatarUrl(): string
    {
        if ($this->avatar) {
            return str_starts_with($this->avatar, 'http')
                ? $this->avatar
                : asset('storage/'.$this->avatar);
        }

        $initials = collect(explode(' ', $this->name))
            ->filter()
            ->take(2)
            ->map(fn (string $part) => mb_substr($part, 0, 1))
            ->implode('');

        $safeInitials = htmlspecialchars(mb_strtoupper($initials ?: 'IG'), ENT_QUOTES, 'UTF-8');
        $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="160" height="160" viewBox="0 0 160 160"><rect width="160" height="160" rx="28" fill="#F59E0B"/><text x="50%" y="54%" dominant-baseline="middle" text-anchor="middle" font-family="Arial, sans-serif" font-size="56" font-weight="800" fill="#0F172A">'.$safeInitials.'</text></svg>';

        return 'data:image/svg+xml;utf8,'.rawurlencode($svg);
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

    public function activityLogs(): HasMany
    {
        return $this->hasMany(GymActivityLog::class, 'actor_id');
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

    public function isReception(): bool
    {
        return $this->role === 'reception';
    }

    public function isMember(): bool
    {
        return $this->role === 'member';
    }

    public function hasBracelet(): bool
    {
        return ! is_null($this->bracelet_uid);
    }

    public function dashboardRoute(): string
    {
        return match ($this->role) {
            'super_admin' => 'super.dashboard',
            'gym_admin' => 'admin.dashboard',
            'coach' => 'coach.dashboard',
            'reception' => 'reception.scanner',
            default => 'member.dashboard',
        };
    }
}
