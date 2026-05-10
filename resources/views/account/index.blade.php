@extends('layouts.app')

@section('title', 'Account | Mafulu')

@section('content')
    @php
        $statusClasses = [
            'pending' => 'status-badge status-pending',
            'screenshot_uploaded' => 'status-badge status-screenshot_uploaded',
            'confirmed' => 'status-badge status-confirmed',
            'delivered' => 'status-badge status-delivered',
            'rejected' => 'status-badge status-rejected',
        ];
    @endphp

    <div class="section-shell">
        <section>
            <div class="section-head">
                <div>
                    <p class="eyebrow">Buyer dashboard</p>
                    <h1 class="section-title">Everything you need after checkout.</h1>
                    <p style="margin-top: 0.75rem; max-width: 760px;">Track order status, open receipts, manage downloads, save products for later, and send support requests without leaving your account.</p>
                </div>
                <a href="{{ route('store.index') }}" class="button-primary">Browse store</a>
            </div>
        </section>

        <section class="stats-grid">
            <article class="surface-card">
                <p class="eyebrow">Orders</p>
                <div class="price-display" style="font-size: 2rem;">{{ $stats['total_orders'] }}</div>
                <p style="margin-top: 0.45rem;">Total purchases linked to your account.</p>
            </article>
            <article class="surface-card">
                <p class="eyebrow">Delivered</p>
                <div class="price-display" style="font-size: 2rem;">{{ $stats['delivered_orders'] }}</div>
                <p style="margin-top: 0.45rem;">Orders with a sent private download link.</p>
            </article>
            <article class="surface-card">
                <p class="eyebrow">Pending</p>
                <div class="price-display" style="font-size: 2rem;">{{ $stats['pending_actions'] }}</div>
                <p style="margin-top: 0.45rem;">Orders still moving through review or needing attention.</p>
            </article>
            <article class="surface-card">
                <p class="eyebrow">Spent</p>
                <div class="price-display" style="font-size: 2rem;">${{ number_format((float) $stats['total_spent'], 2) }}</div>
                <p style="margin-top: 0.45rem;">Lifetime buyer spend across your account.</p>
            </article>
        </section>

        <section class="layout-split-wide" style="align-items: stretch; gap: 1.5rem;">
            <div class="section-shell" style="gap: 1.5rem;">
                <article class="surface-card" style="padding: 0; overflow: hidden;">
                    <div style="padding: 1.5rem 1.5rem 1rem; border-bottom: 1px solid var(--border); display: flex; align-items: flex-end; justify-content: space-between; gap: 1rem; flex-wrap: wrap;">
                        <div>
                            <p class="eyebrow">Recent orders</p>
                            <h2 style="font-size: 1.9rem;">Status at a glance</h2>
                        </div>
                        <div class="mono" style="font-size: 11px; color: var(--ink-faint); letter-spacing: 0.08em; text-transform: uppercase;">{{ $orders->total() }} total linked orders</div>
                    </div>

                    @if ($orders->isEmpty())
                        <div style="padding: 1.5rem; display: grid; gap: 0.6rem;">
                            <p style="color: var(--ink); font-weight: 500;">No orders yet.</p>
                            <p>Once you finish a logged-in checkout, the full status history will appear here.</p>
                        </div>
                    @else
                        <div class="data-table-wrap data-table-scroll" style="border: none; border-radius: 0;">
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th>Order</th>
                                        <th>Product</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($orders as $order)
                                        <tr>
                                            <td class="mono">#{{ $order->id }}</td>
                                            <td>
                                                <div style="display: grid; gap: 0.3rem;">
                                                    <div>{{ $order->product?->title ?? 'Unavailable product' }}</div>
                                                    <div class="mono" style="font-size: 11px; color: var(--ink-faint);">{{ strtoupper($order->crypto_currency) }} {{ number_format((float) $order->crypto_amount, $order->crypto_currency === 'BTC' ? 8 : 2) }}</div>
                                                </div>
                                            </td>
                                            <td class="mono">${{ number_format((float) $order->amount_usd, 2) }}</td>
                                            <td>
                                                <span class="{{ $statusClasses[$order->status] ?? 'status-badge' }}">{{ str($order->status)->replace('_', ' ')->title() }}</span>
                                            </td>
                                            <td>{{ $order->created_at->format('M d, Y') }}</td>
                                            <td>
                                                <div style="display: flex; flex-wrap: wrap; gap: 0.5rem;">
                                                    <a href="{{ route('account.orders.show', $order) }}" class="button-ghost" style="padding: 7px 12px;">View</a>
                                                    <a href="{{ $receiptUrls[$order->id] }}" class="button-ghost" style="padding: 7px 12px;">Receipt</a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="pagination-shell" style="padding: 0 1.5rem 1.5rem;">
                            {{ $orders->links() }}
                        </div>
                    @endif
                </article>

                <div class="two-up">
                    <article class="surface-card">
                        <div style="display: flex; align-items: flex-end; justify-content: space-between; gap: 1rem; margin-bottom: 1.25rem; flex-wrap: wrap;">
                            <div>
                                <p class="eyebrow">Download center</p>
                                <h2 style="font-size: 1.8rem;">Active deliveries</h2>
                            </div>
                            <div class="mono" style="font-size: 11px; color: var(--ink-faint); letter-spacing: 0.08em; text-transform: uppercase;">{{ $downloadOrders->count() }} visible here</div>
                        </div>

                        <div class="stack-list">
                            @forelse ($downloadOrders as $order)
                                <div class="surface-card soft" style="padding: 1rem; display: grid; gap: 0.6rem;">
                                    <div style="display: flex; align-items: center; justify-content: space-between; gap: 1rem; flex-wrap: wrap;">
                                        <div>
                                            <div style="color: var(--ink); font-weight: 500;">{{ $order->product?->title ?? 'Unavailable product' }}</div>
                                            <div class="mono" style="margin-top: 0.25rem; font-size: 11px; color: var(--ink-faint);">Order #{{ $order->id }}</div>
                                        </div>
                                        <span class="{{ $statusClasses[$order->status] ?? 'status-badge' }}">{{ str($order->status)->replace('_', ' ')->title() }}</span>
                                    </div>
                                    <p>{{ $order->downloadIsActive() ? 'Download link is currently live.' : 'The current delivery link is no longer active. Request a refresh from the order page.' }}</p>
                                    <div style="display: flex; flex-wrap: wrap; gap: 0.5rem;">
                                        @if ($order->downloadIsActive())
                                            <a href="{{ route('download', $order->download_token) }}" class="button-primary">Download</a>
                                        @endif
                                        <a href="{{ route('account.orders.show', $order) }}" class="button-ghost">Open order</a>
                                        <a href="{{ $receiptUrls[$order->id] }}" class="button-ghost">Receipt</a>
                                    </div>
                                </div>
                            @empty
                                <p>No delivered orders yet. Approved downloads will appear here automatically.</p>
                            @endforelse
                        </div>
                    </article>

                    <article class="surface-card">
                        <div style="display: flex; align-items: flex-end; justify-content: space-between; gap: 1rem; margin-bottom: 1.25rem; flex-wrap: wrap;">
                            <div>
                                <p class="eyebrow">Saved products</p>
                                <h2 style="font-size: 1.8rem;">Wishlist</h2>
                            </div>
                            <div class="mono" style="font-size: 11px; color: var(--ink-faint); letter-spacing: 0.08em; text-transform: uppercase;">{{ $wishlistItems->count() }} saved</div>
                        </div>

                        <div class="stack-list">
                            @forelse ($wishlistItems as $wishlist)
                                <div class="surface-card soft" style="padding: 1rem; display: grid; gap: 0.6rem;">
                                    <div style="display: flex; align-items: center; justify-content: space-between; gap: 1rem; flex-wrap: wrap;">
                                        <div>
                                            <div style="color: var(--ink); font-weight: 500;">{{ $wishlist->product?->title ?? 'Unavailable product' }}</div>
                                            <div class="mono" style="margin-top: 0.25rem; font-size: 11px; color: var(--ink-faint); text-transform: uppercase;">{{ $wishlist->product?->category ?? 'Saved item' }}</div>
                                        </div>
                                        @if ($wishlist->product)
                                            <div class="price-display" style="font-size: 1.35rem;">${{ number_format((float) $wishlist->product->price_usd, 2) }}</div>
                                        @endif
                                    </div>
                                    <div style="display: flex; flex-wrap: wrap; gap: 0.5rem;">
                                        @if ($wishlist->product)
                                            <a href="{{ route('products.show', $wishlist->product) }}" class="button-ghost">View product</a>
                                            <form method="POST" action="{{ route('wishlist.toggle', $wishlist->product) }}">
                                                @csrf
                                                <button type="submit" class="button-danger">Remove</button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <p>Save products from the store to keep a short list here.</p>
                            @endforelse
                        </div>
                    </article>
                </div>

                @if ($recommendedProducts->isNotEmpty())
                    <article>
                        <div class="section-head" style="margin-bottom: 1.5rem;">
                            <div>
                                <p class="eyebrow">Recommended</p>
                                <h2 class="section-title">More products you may want next.</h2>
                            </div>
                        </div>

                        <div class="catalog-grid">
                            @foreach ($recommendedProducts as $product)
                                <x-product-card :product="$product" />
                            @endforeach
                        </div>
                    </article>
                @endif
            </div>

            <div class="section-shell" style="gap: 1.5rem;">
                <article class="surface-card">
                    <div style="display: flex; align-items: flex-end; justify-content: space-between; gap: 1rem; margin-bottom: 1.25rem; flex-wrap: wrap;">
                        <div>
                            <p class="eyebrow">Notifications</p>
                            <h2 style="font-size: 1.8rem;">Recent account updates</h2>
                        </div>
                        @if ($stats['unread_notifications'] > 0)
                            <form method="POST" action="{{ route('account.notifications.read-all') }}">
                                @csrf
                                <button type="submit" class="button-ghost">Mark all read</button>
                            </form>
                        @endif
                    </div>

                    <div class="stack-list">
                        @forelse ($notifications as $notification)
                            <a href="{{ $notification->action_url ?: route('account.index') }}" class="surface-card {{ is_null($notification->read_at) ? 'interactive' : '' }}" style="padding: 1rem; display: grid; gap: 0.45rem; background: {{ is_null($notification->read_at) ? 'var(--paper-warm)' : 'var(--paper-card)' }};">
                                <div style="display: flex; align-items: center; justify-content: space-between; gap: 1rem; flex-wrap: wrap;">
                                    <div style="color: var(--ink); font-weight: 500;">{{ $notification->title }}</div>
                                    <div class="mono" style="font-size: 11px; color: var(--ink-faint);">{{ $notification->created_at->diffForHumans() }}</div>
                                </div>
                                @if ($notification->body)
                                    <p>{{ $notification->body }}</p>
                                @endif
                                @if ($notification->order)
                                    <div class="mono" style="font-size: 11px; color: var(--ink-faint); text-transform: uppercase; letter-spacing: 0.08em;">Order #{{ $notification->order->id }} @if($notification->order->product) / {{ $notification->order->product->title }} @endif</div>
                                @endif
                            </a>
                        @empty
                            <p>Your account notifications will start appearing after checkout and status changes.</p>
                        @endforelse
                    </div>
                </article>

                <article class="surface-card">
                    <div style="margin-bottom: 1.25rem;">
                        <p class="eyebrow">Profile</p>
                        <h2 style="font-size: 1.8rem;">Buyer details</h2>
                    </div>

                    <form method="POST" action="{{ route('account.profile.update') }}" class="form-grid">
                        @csrf
                        @method('PUT')

                        <div>
                            <label for="name">Full name</label>
                            <input id="name" type="text" name="name" value="{{ old('name', auth()->user()->name) }}" required>
                        </div>

                        <div>
                            <label for="email">Email address</label>
                            <input id="email" type="email" name="email" value="{{ old('email', auth()->user()->email) }}" required>
                        </div>

                        <div>
                            <label for="password">New password</label>
                            <input id="password" type="password" name="password" placeholder="Leave blank to keep your current password">
                        </div>

                        <div>
                            <label for="password_confirmation">Confirm new password</label>
                            <input id="password_confirmation" type="password" name="password_confirmation">
                        </div>

                        <button type="submit" class="button-primary">Save profile</button>
                    </form>
                </article>

                <article class="surface-card">
                    <div style="margin-bottom: 1.25rem;">
                        <p class="eyebrow">Support</p>
                        <h2 style="font-size: 1.8rem;">Open a request</h2>
                    </div>

                    <form method="POST" action="{{ route('account.support.store') }}" class="form-grid">
                        @csrf

                        <div>
                            <label for="support_type">Request type</label>
                            <select id="support_type" name="type">
                                <option value="general">General question</option>
                                <option value="review">Review request</option>
                                <option value="download">Download help</option>
                            </select>
                        </div>

                        <div>
                            <label for="support_order_id">Related order</label>
                            <select id="support_order_id" name="order_id">
                                <option value="">No order selected</option>
                                @foreach ($orders as $order)
                                    <option value="{{ $order->id }}">Order #{{ $order->id }} - {{ $order->product?->title ?? 'Unavailable product' }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="subject">Subject</label>
                            <input id="subject" type="text" name="subject" value="{{ old('subject') }}" required>
                        </div>

                        <div>
                            <label for="message">Message</label>
                            <textarea id="message" name="message" rows="5" required>{{ old('message') }}</textarea>
                        </div>

                        <button type="submit" class="button-primary">Send request</button>
                    </form>
                </article>

                <article class="surface-card">
                    <div style="margin-bottom: 1.25rem;">
                        <p class="eyebrow">Request history</p>
                        <h2 style="font-size: 1.8rem;">Recent support activity</h2>
                    </div>

                    <div class="stack-list">
                        @forelse ($supportRequests as $supportRequest)
                            <div class="surface-card soft" style="padding: 1rem; display: grid; gap: 0.45rem;">
                                <div style="display: flex; align-items: center; justify-content: space-between; gap: 1rem; flex-wrap: wrap;">
                                    <div style="color: var(--ink); font-weight: 500;">{{ $supportRequest->subject }}</div>
                                    <span class="status-badge {{ $supportRequest->status === 'closed' ? 'status-delivered' : 'status-screenshot_uploaded' }}">{{ ucfirst($supportRequest->status) }}</span>
                                </div>
                                <p>{{ $supportRequest->message }}</p>
                                <div class="mono" style="font-size: 11px; color: var(--ink-faint); letter-spacing: 0.08em; text-transform: uppercase;">
                                    {{ $supportRequest->type }}
                                    @if ($supportRequest->order)
                                        / order #{{ $supportRequest->order->id }}
                                    @endif
                                    / {{ $supportRequest->created_at->format('M d, Y') }}
                                </div>
                            </div>
                        @empty
                            <p>No support requests yet.</p>
                        @endforelse
                    </div>
                </article>
            </div>
        </section>
    </div>
@endsection
