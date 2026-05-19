<?php

use App\Models\User;

if (! function_exists('currentGymId')) {
    function currentGymId(): ?int
    {
        $user = auth()->user();

        return $user && ! $user->isSuperAdmin() ? $user->gym_id : null;
    }
}

if (! function_exists('role_home_route')) {
    function role_home_route(?User $user = null): string
    {
        $user ??= auth()->user();

        return $user ? route($user->dashboardRoute()) : route('login');
    }
}

if (! function_exists('ai_recommendation')) {
    function ai_recommendation(?string $goal): array
    {
        return match ($goal) {
            'weight_loss' => [
                'title' => __('messages.recommended_weight_loss'),
                'frequency' => '4x / week',
                'classes' => ['Cardio', 'Crossfit'],
                'reason' => __('messages.recommendation_weight_loss_reason'),
            ],
            'muscle_gain' => [
                'title' => __('messages.recommended_muscle_gain'),
                'frequency' => '4x / week',
                'classes' => ['Strength', 'Boxing'],
                'reason' => __('messages.recommendation_muscle_gain_reason'),
            ],
            'endurance' => [
                'title' => __('messages.recommended_endurance'),
                'frequency' => '3x / week',
                'classes' => ['Cardio', 'Pilates'],
                'reason' => __('messages.recommendation_endurance_reason'),
            ],
            default => [
                'title' => __('messages.recommended_fitness'),
                'frequency' => '3x / week',
                'classes' => ['Yoga', 'Strength'],
                'reason' => __('messages.recommendation_fitness_reason'),
            ],
        };
    }
}

if (! function_exists('status_badge_class')) {
    function status_badge_class(?string $status): string
    {
        return match ($status) {
            'active', 'paid', 'attended', 'success', 'scheduled' => 'bg-emerald-50 text-emerald-700 border-emerald-200 dark:bg-emerald-950/40 dark:text-emerald-300 dark:border-emerald-800',
            'trial', 'reserved', 'info' => 'bg-sky-50 text-sky-700 border-sky-200 dark:bg-sky-950/40 dark:text-sky-300 dark:border-sky-800',
            'expired', 'cancelled', 'danger', 'no_show', 'inactive' => 'bg-rose-50 text-rose-700 border-rose-200 dark:bg-rose-950/40 dark:text-rose-300 dark:border-rose-800',
            'warning', 'unpaid', 'suspended' => 'bg-amber-50 text-amber-700 border-amber-200 dark:bg-amber-950/40 dark:text-amber-300 dark:border-amber-800',
            default => 'bg-slate-50 text-slate-700 border-slate-200 dark:bg-slate-800 dark:text-slate-300 dark:border-slate-700',
        };
    }
}
