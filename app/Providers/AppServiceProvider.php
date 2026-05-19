<?php

namespace App\Providers;

use App\Models\Course;
use App\Models\GymNotification;
use App\Models\Reservation;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Route::bind('member', fn (string $value) => $this->tenantUserQuery('member')->findOrFail($value));
        Route::bind('coach', fn (string $value) => $this->tenantUserQuery('coach')->findOrFail($value));

        Route::bind('course', function (string $value): Course {
            $user = request()->user();

            return Course::query()
                ->when($user && ! $user->isSuperAdmin(), fn (Builder $query) => $query->where('gym_id', $user->gym_id))
                ->when($user?->isCoach(), fn (Builder $query) => $query->where('coach_id', $user->id))
                ->findOrFail($value);
        });

        Route::bind('reservation', function (string $value): Reservation {
            $user = request()->user();

            return Reservation::query()
                ->when($user && ! $user->isSuperAdmin(), fn (Builder $query) => $query->where('gym_id', $user->gym_id))
                ->when($user?->isMember(), fn (Builder $query) => $query->where('user_id', $user->id))
                ->findOrFail($value);
        });

        Route::bind('subscription', function (string $value): Subscription {
            $user = request()->user();

            return Subscription::query()
                ->when($user && ! $user->isSuperAdmin(), fn (Builder $query) => $query->where('gym_id', $user->gym_id))
                ->findOrFail($value);
        });

        Route::bind('notification', function (string $value): GymNotification {
            $user = request()->user();

            return GymNotification::query()
                ->when($user && ! $user->isSuperAdmin(), fn (Builder $query) => $query->where('gym_id', $user->gym_id))
                ->when($user?->isMember(), fn (Builder $query) => $query->where('user_id', $user->id))
                ->findOrFail($value);
        });
    }

    private function tenantUserQuery(string $role): Builder
    {
        $user = request()->user();

        return User::query()
            ->where('role', $role)
            ->when($user && ! $user->isSuperAdmin(), fn (Builder $query) => $query->where('gym_id', $user->gym_id));
    }
}
