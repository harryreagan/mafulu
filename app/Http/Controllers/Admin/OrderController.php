<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\OrderApprovedMail;
use App\Mail\OrderRejectedMail;
use App\Models\Order;
use App\Services\BuyerActivityService;
use App\Services\CouponService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function __construct(
        protected CouponService $couponService,
        protected BuyerActivityService $buyerActivity,
    ) {}

    public function index(): View
    {
        $selectedStatus = request('status');

        return view('admin.orders.index', [
            'orders' => Order::query()
                ->with(['product', 'coupon'])
                ->when(
                    in_array($selectedStatus, Order::statuses(), true),
                    fn ($query) => $query->where('status', $selectedStatus)
                )
                ->latest()
                ->paginate(15)
                ->withQueryString(),
            'selectedStatus' => $selectedStatus,
            'statuses' => Order::statuses(),
        ]);
    }

    public function show(Order $order): View
    {
        return view('admin.orders.show', [
            'order' => $order->load([
                'product',
                'coupon',
                'orderUpdates' => fn ($query) => $query->latest(),
                'downloadAttempts' => fn ($query) => $query->latest('attempted_at'),
            ]),
        ]);
    }

    public function approve(Request $request, Order $order): RedirectResponse
    {
        $validated = $request->validate([
            'notes' => ['nullable', 'string'],
        ]);

        $wasDelivered = $order->status === Order::STATUS_DELIVERED;

        $order->forceFill([
            'status' => Order::STATUS_DELIVERED,
            'download_token' => (string) Str::uuid(),
            'token_expires_at' => now()->addHours(48),
            'approved_at' => $order->approved_at ?? now(),
            'notes' => $validated['notes'] ?? $order->notes,
        ])->save();

        if (! $wasDelivered) {
            $order->product()->increment('sales_count');
            $this->couponService->redeem($order->coupon);
        }

        $freshOrder = $order->fresh(['product', 'coupon', 'user']);

        Mail::to($freshOrder->buyer_email)->send(new OrderApprovedMail($freshOrder));
        $this->buyerActivity->orderApproved($freshOrder);

        return redirect()
            ->route('admin.orders.show', $order)
            ->with('status', 'Order approved and download link emailed.');
    }

    public function reject(Request $request, Order $order): RedirectResponse
    {
        $validated = $request->validate([
            'notes' => ['nullable', 'string'],
        ]);

        $note = trim((string) ($validated['notes'] ?? ''));

        $order->update([
            'status' => Order::STATUS_REJECTED,
            'notes' => $note !== '' ? $note : 'Payment proof rejected. Please review the notes and submit a fresh request.',
            'download_token' => null,
            'token_expires_at' => null,
        ]);

        $freshOrder = $order->fresh(['product', 'coupon', 'user']);

        Mail::to($freshOrder->buyer_email)->send(new OrderRejectedMail($freshOrder));
        $this->buyerActivity->orderRejected($freshOrder);

        return redirect()
            ->route('admin.orders.show', $order)
            ->with('status', 'Order marked as rejected and buyer notified.');
    }

    public function refreshDownload(Order $order): RedirectResponse
    {
        abort_unless($order->status === Order::STATUS_DELIVERED, 422);

        $order->forceFill([
            'download_token' => (string) Str::uuid(),
            'token_expires_at' => now()->addHours(48),
        ])->save();

        $freshOrder = $order->fresh(['product', 'coupon', 'user']);

        Mail::to($freshOrder->buyer_email)->send(new OrderApprovedMail($freshOrder));
        $this->buyerActivity->downloadRefreshed($freshOrder);

        return redirect()->route('admin.orders.show', $order)->with('status', 'Download link refreshed and emailed again.');
    }

    public function screenshot(Order $order)
    {
        abort_unless($order->screenshot_path && Storage::disk('local')->exists($order->screenshot_path), 404);

        return response()->file(Storage::disk('local')->path($order->screenshot_path));
    }
}
