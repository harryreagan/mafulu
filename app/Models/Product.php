<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    public const CATEGORY_EBOOK = 'ebook';
    public const CATEGORY_TEMPLATE = 'template';
    public const CATEGORY_SOFTWARE = 'software';
    public const CATEGORY_COURSE = 'course';

    protected $fillable = [
        'title',
        'slug',
        'description',
        'category',
        'price_usd',
        'file_path',
        'preview_path',
        'is_active',
        'sales_count',
    ];

    protected $casts = [
        'price_usd' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public static function categories(): array
    {
        return [
            self::CATEGORY_EBOOK,
            self::CATEGORY_TEMPLATE,
            self::CATEGORY_SOFTWARE,
            self::CATEGORY_COURSE,
        ];
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function wishlists(): HasMany
    {
        return $this->hasMany(Wishlist::class);
    }

    public function wishlistedBy(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'wishlists')->withTimestamps();
    }

    public function hasPreview(): bool
    {
        return filled($this->preview_path) && Storage::disk('local')->exists($this->preview_path);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
