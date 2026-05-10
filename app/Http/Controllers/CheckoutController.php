<?php

namespace App\Http\Controllers;

use App\Mail\NewOrderSubmittedMail;
use App\Models\Order;
use App\Models\Product;
use App\Services\BuyerActivityService;
use App\Services\CouponService;
use App\Services\CryptoRateService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;

class CheckoutController extends Controller
{
    public function __construct(
        protected CryptoRateService $cryptoRateService,
        protected CouponService $couponService,
        protected BuyerActivityService $buyerActivity,
    ) {}

    public function show(Product $product): View
    {
        abort_unless($product->is_active, 404);

        $pricing = $this->couponService->apply(request('coupon'), (float) $product->price_usd);
        $rates = $this->cryptoRateService->currentRates();

        return view('checkout.show', [
            'product' => $product,
            'wallets' => config('mafullu.wallets'),
            'rates' => $rates,
            'pricing' => $pricing,
            'quotes' => [
                Order::CRYPTO_BTC => $this->cryptoRateService->quote($pricing['final_amount_usd'], Order::CRYPTO_BTC),
                Order::CRYPTO_USDT => $this->cryptoRateService->quote($pricing['final_amount_usd'], Order::CRYPTO_USDT),
            ],
        ]);
    }

    public function store(Request $request, Product $product): RedirectResponse
    {
        abort_unless($product->is_active, 404);

        $validated = $request->validate([
            'buyer_name' => ['required', 'string', 'max:255'],
            'buyer_email' => ['required', 'email', 'max:255'],
            'crypto_currency' => ['required', 'in:'.implode(',', Order::cryptocurrencies())],
            'coupon_code' => ['nullable', 'string', 'max:50'],
            'payment_screenshot' => ['required', 'file', 'image', 'max:5120'],
        ]);

        $pricing = $this->couponService->apply($validated['coupon_code'] ?? null, (float) $product->price_usd);
        $quote = $this->cryptoRateService->quote($pricing['final_amount_usd'], $validated['crypto_currency']);

        $order = Order::create([
            'buyer_name' => $validated['buyer_name'],
            'buyer_email' => $validated['buyer_email'],
            'user_id' => $request->user()->id,
            'product_id' => $product->id,
            'coupon_id' => $pricing['coupon']?->id,
            'coupon_code' => $pricing['coupon']?->code,
            'discount_usd' => $pricing['discount_usd'],
            'amount_usd' => $pricing['final_amount_usd'],
            'crypto_currency' => $validated['crypto_currency'],
            'crypto_amount' => $quote['crypto_amount'],
            'crypto_rate_used' => $quote['rate_used'],
            'status' => Order::STATUS_SCREENSHOT_UPLOADED,
            'screenshot_path' => $request->file('payment_screenshot')->store('payment-screenshots', 'local'),
        ]);

        $this->buyerActivity->orderSubmitted($order->fresh(['product', 'user']));

        if (filled(config('mafullu.admin_email'))) {
            Mail::to(config('mafullu.admin_email'))->send(new NewOrderSubmittedMail($order->fresh(['product', 'coupon'])));
        }

        return redirect()->route('checkout.confirmation', $order);
    }

    public function confirmation(Request $request, Order $order): View
    {
        abort_unless($order->belongsToUser($request->user()), 404);

        return view('checkout.confirmation', [
            'order' => $order->load('product'),
            'receiptUrl' => URL::temporarySignedRoute('orders.receipt', now()->addDays(7), ['order' => $order]),
        ]);
    }
}
