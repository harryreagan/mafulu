<?php

namespace App\Http\Controllers;

use App\Models\BuyerSupportRequest;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\Wishlist;
use App\Services\BuyerActivityService;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class AccountController extends Controller
{
    public function __construct(protected BuyerActivityService $buyerActivity) {}

    public function index(): View
    {
        /** @var User $user */
        $user = Auth::user();
        $user->loadMissing('wishlists.product');

        $ordersQuery = $this->buyerOrdersQuery($user);

        $orders = (clone $ordersQuery)
            ->with(['product', 'coupon'])
            ->latest()
            ->paginate(8);

        $downloadOrders = (clone $ordersQuery)
            ->with('product')
            ->where('status', Order::STATUS_DELIVERED)
            ->latest()
            ->take(5)
            ->get();

        $receiptOrders = $orders->getCollection()->concat($downloadOrders)->unique('id');
        $receiptUrls = $receiptOrders->mapWithKeys(fn (Order $order) => [
            $order->id => URL::temporarySignedRoute('orders.receipt', now()->addDays(7), ['order' => $order]),
        ]);

        $notifications = $user->buyerNotifications()
            ->with(['order.product'])
            ->orderByRaw('read_at IS NULL DESC')
            ->latest()
            ->take(8)
            ->get();

        $supportRequests = $user->supportRequests()
            ->with(['order.product'])
            ->latest()
            ->take(6)
            ->get();

        $wishlistItems = $user->wishlists()->with('product')->latest()->take(6)->get();
        $purchasedProductIds = (clone $ordersQuery)->pluck('product_id');
        $wishlistProductIds = $wishlistItems->pluck('product_id');
        $preferredCategories = (clone $ordersQuery)
            ->with('product:id,category')
            ->get()
            ->pluck('product.category')
            ->merge($wishlistItems->pluck('product.category'))
            ->filter()
            ->unique()
            ->values();

        $recommendedProducts = Product::query()
            ->active()
            ->whereNotIn('id', $purchasedProductIds->merge($wishlistProductIds)->unique()->values())
            ->when(
                $preferredCategories->isNotEmpty(),
                fn ($query) => $query->whereIn('category', $preferredCategories)
            )
            ->latest()
            ->take(4)
            ->get();

        return view('account.index', [
            'orders' => $orders,
            'downloadOrders' => $downloadOrders,
            'notifications' => $notifications,
            'supportRequests' => $supportRequests,
            'wishlistItems' => $wishlistItems,
            'recommendedProducts' => $recommendedProducts,
            'receiptUrls' => $receiptUrls,
            'stats' => [
                'total_orders' => (clone $ordersQuery)->count(),
                'delivered_orders' => (clone $ordersQuery)->where('status', Order::STATUS_DELIVERED)->count(),
                'pending_actions' => (clone $ordersQuery)->whereIn('status', [
                    Order::STATUS_PENDING,
                    Order::STATUS_SCREENSHOT_UPLOADED,
                    Order::STATUS_CONFIRMED,
                    Order::STATUS_REJECTED,
                ])->count(),
                'total_spent' => (float) (clone $ordersQuery)->sum('amount_usd'),
                'unread_notifications' => $notifications->whereNull('read_at')->count(),
            ],
        ]);
    }

    public function show(Request $request, Order $order): View
    {
        /** @var User $user */
        $user = $request->user();

        $order = $this->ensureBuyerOwnsOrder(
            $order->load([
                'product',
                'coupon',
                'orderUpdates' => fn ($query) => $query->latest(),
                'downloadAttempts' => fn ($query) => $query->latest('attempted_at'),
                'supportRequests' => fn ($query) => $query->latest(),
            ]),
            $user
        );

        $user->buyerNotifications()
            ->where('order_id', $order->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return view('account.show', [
            'order' => $order,
            'receiptUrl' => URL::temporarySignedRoute('orders.receipt', now()->addDays(7), ['order' => $order]),
            'relatedProducts' => Product::query()
                ->active()
                ->where('category', $order->product?->category)
                ->whereKeyNot($order->product_id)
                ->latest()
                ->take(3)
                ->get(),
        ]);
    }

    public function updateProfile(Request $request): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => ['nullable', 'confirmed', Password::min(8)],
        ]);

        $user->fill([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        if (filled($validated['password'] ?? null)) {
            $user->password = $validated['password'];
        }

        $user->save();

        $this->buyerActivity->profileUpdated($user);

        return back()->with('status', 'Your buyer profile has been updated.');
    }

    public function markNotificationsRead(Request $request): RedirectResponse
    {
        $request->user()->buyerNotifications()->whereNull('read_at')->update(['read_at' => now()]);

        return back()->with('status', 'Notifications marked as read.');
    }

    public function storeSupport(Request $request): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();

        $validated = $request->validate([
            'order_id' => ['nullable', 'integer', 'exists:orders,id'],
            'type' => ['required', Rule::in([
                BuyerSupportRequest::TYPE_GENERAL,
                BuyerSupportRequest::TYPE_REVIEW,
                BuyerSupportRequest::TYPE_DOWNLOAD,
            ])],
            'subject' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:2000'],
        ]);

        $order = null;

        if (! empty($validated['order_id'])) {
            $order = $this->ensureBuyerOwnsOrder(Order::query()->findOrFail($validated['order_id']), $user);
        }

        $this->openSupportRequest(
            $user,
            $order,
            $validated['type'],
            $validated['subject'],
            $validated['message']
        );

        return back()->with('status', 'Your support request has been logged in the dashboard.');
    }

    public function requestReview(Request $request, Order $order): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();
        $order = $this->ensureBuyerOwnsOrder($order->load('product'), $user);

        $validated = $request->validate([
            'message' => ['nullable', 'string', 'max:2000'],
        ]);

        $message = trim((string) ($validated['message'] ?? ''));

        $this->openSupportRequest(
            $user,
            $order,
            BuyerSupportRequest::TYPE_REVIEW,
            'Manual review requested for order #'.$order->id,
            $message !== ''
                ? $message
                : 'Buyer requested a fresh review of the payment proof and order notes.'
        );

        return back()->with('status', 'Review request submitted.');
    }

    public function requestDownload(Request $request, Order $order): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();
        $order = $this->ensureBuyerOwnsOrder($order->load('product'), $user);

        $validated = $request->validate([
            'message' => ['nullable', 'string', 'max:2000'],
        ]);

        $message = trim((string) ($validated['message'] ?? ''));

        $this->openSupportRequest(
            $user,
            $order,
            BuyerSupportRequest::TYPE_DOWNLOAD,
            'Download refresh requested for order #'.$order->id,
            $message !== ''
                ? $message
                : 'Buyer requested a fresh private delivery link for this order.'
        );

        return back()->with('status', 'Download refresh request submitted.');
    }

    public function toggleWishlist(Request $request, Product $product): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();

        $existing = Wishlist::query()
            ->where('user_id', $user->id)
            ->where('product_id', $product->id)
            ->first();

        if ($existing) {
            $existing->delete();

            return back()->with('status', 'Removed from saved products.');
        }

        Wishlist::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);

        return back()->with('status', 'Saved to your buyer dashboard.');
    }

    protected function buyerOrdersQuery(User $user): Builder
    {
        return Order::query()->where(function ($query) use ($user) {
            $query
                ->where('user_id', $user->id)
                ->orWhere('buyer_email', $user->email);
        });
    }

    protected function ensureBuyerOwnsOrder(Order $order, User $user): Order
    {
        abort_unless($order->belongsToUser($user), 404);

        return $order;
    }

    protected function openSupportRequest(User $user, ?Order $order, string $type, string $subject, string $message): BuyerSupportRequest
    {
        $supportRequest = BuyerSupportRequest::create([
            'user_id' => $user->id,
            'order_id' => $order?->id,
            'type' => $type,
            'subject' => $subject,
            'message' => $message,
            'status' => BuyerSupportRequest::STATUS_OPEN,
        ]);

        $supportRequest->setRelation('user', $user);
        $supportRequest->setRelation('order', $order);

        $this->buyerActivity->supportRequestLogged($supportRequest);

        return $supportRequest;
    }
}
