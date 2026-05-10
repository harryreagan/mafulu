<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    public const TYPE_FIXED = 'fixed';
    public const TYPE_PERCENTAGE = 'percentage';

    protected $fillable = [
        'code',
        'type',
        'value',
        'is_active',
        'usage_limit',
        'times_redeemed',
        'expires_at',
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'is_active' => 'boolean',
        'expires_at' => 'datetime',
    ];

    public static function types(): array
    {
        return [
            self::TYPE_FIXED,
            self::TYPE_PERCENTAGE,
        ];
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query
            ->where('is_active', true)
            ->where(function (Builder $builder) {
                $builder
                    ->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            })
            ->where(function (Builder $builder) {
                $builder
                    ->whereNull('usage_limit')
                    ->orWhereColumn('times_redeemed', '<', 'usage_limit');
            });
    }
}
