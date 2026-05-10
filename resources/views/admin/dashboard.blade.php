@extends('layouts.admin')

@section('title', 'Dashboard | Mafulu Admin')

@section('content')
    <div style="display: grid; gap: 2rem;">
        <div class="section-head">
            <div>
                <p class="eyebrow">Dashboard</p>
                <h1 class="section-title">Sales and delivery overview</h1>
            </div>
        </div>

        <div class="stats-grid">
            <div class="surface-card">
                <p class="eyebrow">Delivered revenue</p>
                <h2 style="font-size: 2.1rem;">${{ number_format($stats['revenue'], 2) }}</h2>
            </div>
            <div class="surface-card">
                <p class="eyebrow">Delivered orders</p>
                <h2 style="font-size: 2.1rem;">{{ $stats['delivered_orders'] }}</h2>
            </div>
            <div class="surface-card">
                <p class="eyebrow">Pending reviews</p>
                <h2 style="font-size: 2.1rem;">{{ $stats['pending_reviews'] }}</h2>
            </div>
            <div class="surface-card">
                <p class="eyebrow">Coupon redemptions</p>
                <h2 style="font-size: 2.1rem;">{{ $stats['coupon_redemptions'] }}</h2>
            </div>
        </div>

        <div class="layout-dashboard">
            <section class="surface-card">
                <div class="section-head" style="margin-bottom: 1.5rem;">
                    <div>
                        <p class="eyebrow">Catalog</p>
                        <h2 style="font-size: 1.8rem;">Top products</h2>
                    </div>
                </div>
                <div style="display: grid; gap: 0.85rem;">
                    @forelse ($topProducts as $product)
                        <div class="surface-card" style="padding: 1rem 1.1rem;">
                            <div style="display: flex; align-items: center; justify-content: space-between; gap: 1rem;">
                                <div>
                                    <div style="font-weight: 500; color: var(--ink);">{{ $product->title }}</div>
                                    <div class="mono" style="margin-top: 4px; color: var(--ink-faint); font-size: 11px; letter-spacing: 0.08em; text-transform: uppercase;">{{ $product->category }}</div>
                                </div>
                                <div class="mono" style="color: var(--ink-muted);">{{ $product->sales_count }} sales</div>
                            </div>
                        </div>
                    @empty
                        <p>No product sales yet.</p>
                    @endforelse
                </div>
            </section>

            <section class="surface-card">
                <div class="section-head" style="margin-bottom: 1.5rem;">
                    <div>
                        <p class="eyebrow">Orders</p>
                        <h2 style="font-size: 1.8rem;">Recent orders</h2>
                    </div>
                </div>
                <div style="display: grid; gap: 0.85rem;">
                    @forelse ($recentOrders as $order)
                        <a href="{{ route('admin.orders.show', $order) }}" class="surface-card" style="padding: 1rem 1.1rem; transition: border-color 0.2s ease, transform 0.2s ease;">
                            <div style="display: flex; align-items: center; justify-content: space-between; gap: 1rem;">
                                <div>
                                    <div style="font-weight: 500; color: var(--ink);">#{{ $order->id }} · {{ $order->product->title }}</div>
                                    <div style="font-size: 14px; color: var(--ink-muted);">{{ $order->buyer_name }} · ${{ number_format((float) $order->amount_usd, 2) }}</div>
                                </div>
                                <span class="status-badge status-{{ $order->status }}">{{ str_replace('_', ' ', $order->status) }}</span>
                            </div>
                        </a>
                    @empty
                        <p>No orders yet.</p>
                    @endforelse
                </div>
            </section>
        </div>

        <section class="surface-card" style="padding: 0; overflow: hidden;">
            <div class="section-head" style="padding: 1.75rem 1.75rem 1rem; margin-bottom: 0;">
                <div>
                    <p class="eyebrow">Security</p>
                    <h2 style="font-size: 1.8rem;">Recent download attempts</h2>
                </div>
            </div>
            <div class="data-table-wrap data-table-scroll" style="border: none; border-radius: 0;">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Order</th>
                            <th>Result</th>
                            <th>Reason</th>
                            <th>When</th>
                            <th>IP</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($recentAttempts as $attempt)
                            <tr>
                                <td>{{ $attempt->order?->id ? '#'.$attempt->order->id : 'Unknown' }}</td>
                                <td>{{ $attempt->was_successful ? 'Success' : 'Failed' }}</td>
                                <td>{{ $attempt->failure_reason ?: '-' }}</td>
                                <td>{{ $attempt->attempted_at->diffForHumans() }}</td>
                                <td class="mono">{{ $attempt->ip_address ?: '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5">No download attempts logged yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </div>
@endsection

