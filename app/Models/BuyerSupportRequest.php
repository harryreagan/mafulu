<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BuyerSupportRequest extends Model
{
    public const TYPE_GENERAL = 'general';
    public const TYPE_REVIEW = 'review';
    public const TYPE_DOWNLOAD = 'download';

    public const STATUS_OPEN = 'open';
    public const STATUS_CLOSED = 'closed';

    protected $fillable = [
        'user_id',
        'order_id',
        'type',
        'subject',
        'message',
        'status',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
