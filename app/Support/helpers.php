<?php

use App\Models\GymActivityLog;
use App\Models\GymNotification;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

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

if (! function_exists('igym_navigation_items')) {
    function igym_navigation_items(?User $user = null): array
    {
        $user ??= auth()->user();

        if (! $user) {
            return [];
        }

        return match ($user->role) {
            'super_admin' => [
                ['label' => __('messages.dashboard'), 'route' => 'super.dashboard', 'active' => 'super.dashboard', 'icon' => 'dashboard'],
                ['label' => __('messages.gyms'), 'route' => 'super.gyms.index', 'active' => 'super.gyms.*', 'icon' => 'building'],
            ],
            'gym_admin' => [
                ['label' => __('messages.dashboard'), 'route' => 'admin.dashboard', 'active' => 'admin.dashboard', 'icon' => 'dashboard'],
                ['label' => __('messages.members'), 'route' => 'admin.members.index', 'active' => 'admin.members.*', 'icon' => 'users'],
                ['label' => __('messages.coaches'), 'route' => 'admin.coaches.index', 'active' => 'admin.coaches.*', 'icon' => 'coach'],
                ['label' => __('messages.courses'), 'route' => 'admin.courses.index', 'active' => 'admin.courses.*', 'icon' => 'calendar'],
                ['label' => __('messages.reservations'), 'route' => 'admin.reservations.index', 'active' => 'admin.reservations.*', 'icon' => 'attendance'],
                ['label' => __('messages.subscriptions'), 'route' => 'admin.subscriptions.index', 'active' => 'admin.subscriptions.*', 'icon' => 'credit-card'],
                ['label' => __('messages.attendance'), 'route' => 'admin.attendance.index', 'active' => 'admin.attendance.*', 'icon' => 'qr'],
                ['label' => __('messages.notifications'), 'route' => 'admin.notifications.index', 'active' => 'admin.notifications.*', 'icon' => 'bell'],
                ['label' => __('messages.activity_logs'), 'route' => 'admin.logs.index', 'active' => 'admin.logs.*', 'icon' => 'list'],
            ],
            'coach' => [
                ['label' => __('messages.dashboard'), 'route' => 'coach.dashboard', 'active' => 'coach.dashboard', 'icon' => 'dashboard'],
                ['label' => __('messages.classes'), 'route' => 'coach.classes.index', 'active' => 'coach.classes.*', 'icon' => 'calendar'],
                ['label' => __('messages.members'), 'route' => 'coach.members.index', 'active' => 'coach.members.*', 'icon' => 'users'],
                ['label' => __('messages.training_plans'), 'route' => 'coach.training-plans.index', 'active' => 'coach.training-plans.*', 'icon' => 'target'],
                ['label' => __('messages.progress'), 'route' => 'coach.progress.index', 'active' => 'coach.progress.*', 'icon' => 'activity'],
            ],
            'reception' => [
                ['label' => __('messages.reception_scanner'), 'route' => 'reception.scanner', 'active' => 'reception.*', 'icon' => 'scan'],
            ],
            default => [
                ['label' => __('messages.dashboard'), 'route' => 'member.dashboard', 'active' => 'member.dashboard', 'icon' => 'dashboard'],
                ['label' => __('messages.qr_code'), 'route' => 'member.qr-code', 'active' => 'member.qr-code', 'icon' => 'qr'],
                ['label' => __('messages.courses'), 'route' => 'member.courses.index', 'active' => 'member.courses.*', 'icon' => 'calendar'],
                ['label' => __('messages.reservations'), 'route' => 'member.reservations.index', 'active' => 'member.reservations.*', 'icon' => 'attendance'],
                ['label' => __('messages.subscription'), 'route' => 'member.subscription', 'active' => 'member.subscription', 'icon' => 'credit-card'],
                ['label' => __('messages.progress'), 'route' => 'member.progress', 'active' => 'member.progress', 'icon' => 'activity'],
                ['label' => __('messages.notifications'), 'route' => 'member.notifications.index', 'active' => 'member.notifications.*', 'icon' => 'bell'],
            ],
        };
    }
}

if (! function_exists('igym_current_page_title')) {
    function igym_current_page_title(?User $user = null): string
    {
        if (request()->routeIs('profile.*')) {
            return __('messages.profile');
        }

        if (request()->routeIs('settings.language')) {
            return __('messages.language');
        }

        if (request()->routeIs('settings.theme')) {
            return __('messages.theme');
        }

        foreach (igym_navigation_items($user) as $item) {
            if (request()->routeIs($item['active'])) {
                return $item['label'];
            }
        }

        return __('messages.smart_fitness_management');
    }
}

if (! function_exists('igym_notification_route')) {
    function igym_notification_route(?User $user = null): ?string
    {
        $user ??= auth()->user();

        return match ($user?->role) {
            'gym_admin' => route('admin.notifications.index'),
            'member' => route('member.notifications.index'),
            default => null,
        };
    }
}

if (! function_exists('igym_unread_notification_count')) {
    function igym_unread_notification_count(?User $user = null): int
    {
        $user ??= auth()->user();

        if (! $user || ! $user->isMember()) {
            return 0;
        }

        return GymNotification::where('gym_id', $user->gym_id)
            ->where('user_id', $user->id)
            ->where('is_read', false)
            ->count();
    }
}

if (! function_exists('record_gym_activity')) {
    function record_gym_activity(
        ?int $gymId,
        string $action,
        string $description,
        ?Model $subject = null,
        ?User $actor = null,
        array $metadata = []
    ): ?GymActivityLog {
        if (! $gymId) {
            return null;
        }

        $actor ??= auth()->user();

        return GymActivityLog::create([
            'gym_id' => $gymId,
            'actor_id' => $actor?->id,
            'subject_type' => $subject ? $subject::class : null,
            'subject_id' => $subject?->getKey(),
            'action' => $action,
            'description' => $description,
            'metadata' => $metadata ?: null,
        ]);
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
            'qr', 'manual' => 'bg-violet-50 text-violet-700 border-violet-200 dark:bg-violet-950/40 dark:text-violet-300 dark:border-violet-800',
            'trial', 'reserved', 'info' => 'bg-sky-50 text-sky-700 border-sky-200 dark:bg-sky-950/40 dark:text-sky-300 dark:border-sky-800',
            'expired', 'cancelled', 'danger', 'no_show', 'inactive' => 'bg-rose-50 text-rose-700 border-rose-200 dark:bg-rose-950/40 dark:text-rose-300 dark:border-rose-800',
            'warning', 'unpaid', 'suspended' => 'bg-amber-50 text-amber-700 border-amber-200 dark:bg-amber-950/40 dark:text-amber-300 dark:border-amber-800',
            default => 'bg-slate-50 text-slate-700 border-slate-200 dark:bg-slate-800 dark:text-slate-300 dark:border-slate-700',
        };
    }
}

if (! function_exists('currency_symbol')) {
    function currency_symbol(?string $currency = null): string
    {
        $currency ??= auth()->user()?->currency ?? 'MAD';

        return match ($currency) {
            'USD' => '$',
            'EUR' => '€',
            'GBP' => '£',
            'MAD' => 'MAD',
            default => $currency,
        };
    }
}

if (! function_exists('format_currency')) {
    function format_currency(float|int|string|null $amount, ?string $currency = null): string
    {
        $amount = (float) ($amount ?? 0);
        $currency ??= auth()->user()?->currency ?? 'MAD';
        $symbol = currency_symbol($currency);
        $formatted = number_format($amount, 2);

        return $currency === 'MAD' ? $formatted.' '.$symbol : $symbol.$formatted;
    }
}
