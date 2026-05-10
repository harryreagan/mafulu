<?php

namespace App\Http\Controllers;

use App\Models\DownloadAttempt;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DownloadController extends Controller
{
    public function __invoke(Request $request, string $token)
    {
        $order = Order::query()
            ->with('product')
            ->where('download_token', $token)
            ->first();

        if (! $order) {
            $this->logAttempt($request, null, $token, false, 'invalid_token');

            abort(404);
        }

        if ($order->status !== Order::STATUS_DELIVERED) {
            $this->logAttempt($request, $order, $token, false, 'invalid_status');

            abort(403, 'This download link is not available.');
        }

        if (blank($order->token_expires_at) || $order->token_expires_at->isPast()) {
            $this->logAttempt($request, $order, $token, false, 'expired');

            abort(403, 'This download link has expired.');
        }

        if (! Storage::disk('local')->exists($order->product->file_path)) {
            $this->logAttempt($request, $order, $token, false, 'missing_file');

            abort(404);
        }

        $downloadName = $order->product->slug.'.'.pathinfo($order->product->file_path, PATHINFO_EXTENSION);

        $order->forceFill([
            'download_token' => null,
            'token_expires_at' => null,
        ])->save();

        $this->logAttempt($request, $order, $token, true);

        return Storage::disk('local')->download($order->product->file_path, $downloadName);
    }

    protected function logAttempt(Request $request, ?Order $order, ?string $token, bool $successful, ?string $reason = null): void
    {
        DownloadAttempt::create([
            'order_id' => $order?->id,
            'token' => $token,
            'ip_address' => $request->ip(),
            'user_agent' => (string) $request->userAgent(),
            'was_successful' => $successful,
            'failure_reason' => $reason,
            'attempted_at' => now(),
        ]);
    }
}
