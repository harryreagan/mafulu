<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    public const STATUS_PENDING = 'pending';
    public const STATUS_SCREENSHOT_UPLOADED = 'screenshot_uploaded';
    public const STATUS_CONFIRMED = 'confirmed';
    public const STATUS_DELIVERED = 'delivered';
    public const STATUS_REJECTED = 'rejected';

    public const CRYPTO_BTC = 'BTC';
    public const CRYPTO_USDT = 'USDT';

    protected $fillable = [
        'buyer_name',
        'buyer_email',
        'user_id',
        'product_id',
        'coupon_id',
        'coupon_code',
        'discount_usd',
        'amount_usd',
        'crypto_currency',
        'crypto_amount',
        'crypto_rate_used',
        'status',
        'screenshot_path',
        'download_token',
        'token_expires_at',
        'approved_at',
        'notes',
    ];

    protected $casts = [
        'discount_usd' => 'decimal:2',
        'amount_usd' => 'decimal:2',
        'crypto_amount' => 'decimal:8',
        'crypto_rate_used' => 'decimal:8',
        'token_expires_at' => 'datetime',
        'approved_at' => 'datetime',
    ];

    public static function statuses(): array
    {
        return [
            self::STATUS_PENDING,
            self::STATUS_SCREENSHOT_UPLOADED,
            self::STATUS_CONFIRMED,
            self::STATUS_DELIVERED,
            self::STATUS_REJECTED,
        ];
    }

    public static function cryptocurrencies(): array
    {
        return [
            self::CRYPTO_BTC,
            self::CRYPTO_USDT,
        ];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function coupon(): BelongsTo
    {
        return $this->belongsTo(Coupon::class);
    }

    public function downloadAttempts(): HasMany
    {
        return $this->hasMany(DownloadAttempt::class);
    }

    public function orderUpdates(): HasMany
    {
        return $this->hasMany(OrderUpdate::class)->latest();
    }

    public function buyerNotifications(): HasMany
    {
        return $this->hasMany(BuyerNotification::class)->latest();
    }

    public function supportRequests(): HasMany
    {
        return $this->hasMany(BuyerSupportRequest::class)->latest();
    }

    public function belongsToUser(User $user): bool
    {
        return $this->user_id === $user->id || strcasecmp($this->buyer_email, $user->email) === 0;
    }

    public function downloadIsActive(): bool
    {
        return $this->status === self::STATUS_DELIVERED
            && filled($this->download_token)
            && $this->token_expires_at?->isFuture();
    }
}
