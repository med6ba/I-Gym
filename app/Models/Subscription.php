<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Subscription extends Model
{
    use HasFactory;

    public const PLAN_PRIMARY = 'Primary';
    public const PLAN_PERSONAL_COACHING = 'Personal Coaching';

    protected $fillable = [
        'gym_id',
        'user_id',
        'plan_name',
        'price',
        'starts_at',
        'ends_at',
        'status',
        'payment_status',
    ];

    protected function casts(): array
    {
        return [
            'starts_at' => 'date',
            'ends_at' => 'date',
            'price' => 'decimal:2',
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

    public function scopeForCurrentGym(Builder $query): Builder
    {
        if (auth()->check() && ! auth()->user()->isSuperAdmin()) {
            $query->where('gym_id', auth()->user()->gym_id);
        }

        return $query;
    }

    public function scopeExpiringSoon(Builder $query): Builder
    {
        return $query->where('status', 'active')
            ->whereBetween('ends_at', [now()->toDateString(), now()->addDays(7)->toDateString()]);
    }

    /**
     * @return array<string, array{name: string, price: int}>
     */
    public static function plans(): array
    {
        return [
            self::PLAN_PRIMARY => [
                'name' => __('messages.primary_subscription'),
                'price' => 299,
            ],
            self::PLAN_PERSONAL_COACHING => [
                'name' => __('messages.personal_coaching_subscription'),
                'price' => 499,
            ],
        ];
    }

    public static function priceForPlan(string $planName): int
    {
        return self::plans()[$planName]['price'] ?? self::plans()[self::PLAN_PRIMARY]['price'];
    }

    public static function labelForPlan(?string $planName): string
    {
        if (! $planName) {
            return __('messages.no_data');
        }

        return self::plans()[$planName]['name'] ?? $planName;
    }
}
