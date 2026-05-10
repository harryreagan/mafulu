<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class DownloadAttempt extends Model
{
    protected $fillable = [
        'order_id',
        'token',
        'ip_address',
        'user_agent',
        'was_successful',
        'failure_reason',
        'attempted_at',
    ];

    protected $casts = [
        'was_successful' => 'boolean',
        'attempted_at' => 'datetime',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
