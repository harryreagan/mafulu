<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\DownloadAttempt;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        return view('admin.dashboard', [
            'stats' => [
                'revenue' => (float) Order::query()->where('status', Order::STATUS_DELIVERED)->sum('amount_usd'),
                'delivered_orders' => Order::query()->where('status', Order::STATUS_DELIVERED)->count(),
                'pending_reviews' => Order::query()->where('status', Order::STATUS_SCREENSHOT_UPLOADED)->count(),
                'coupon_redemptions' => Coupon::query()->sum('times_redeemed'),
            ],
            'topProducts' => Product::query()->orderByDesc('sales_count')->take(5)->get(),
            'recentOrders' => Order::query()->with('product')->latest()->take(6)->get(),
            'recentAttempts' => DownloadAttempt::query()->with('order.product')->latest('attempted_at')->take(8)->get(),
        ]);
    }
}
