@php
    $statusClasses = [
        'pending' => 'status-badge status-pending',
        'screenshot_uploaded' => 'status-badge status-screenshot_uploaded',
        'confirmed' => 'status-badge status-confirmed',
        'delivered' => 'status-badge status-delivered',
    ];
@endphp

@extends('layouts.admin')

@section('title', 'Order #' . $order->id . ' | Mafulu Admin')

@section('content')
    <div style="display: grid; gap: 2rem;">
        <div class="section-head">
            <div>
                <p class="eyebrow">Order detail</p>
                <h1 class="section-title">Order #{{ $order->id }}</h1>
            </div>
            <div style="display: flex; flex-wrap: wrap; gap: 10px; align-items: center;">
                <span class="{{ $statusClasses[$order->status] ?? 'status-badge' }}">{{ str_replace('_', ' ', $order->status) }}</span>
                <a href="{{ route('admin.orders.receipt', $order) }}" class="button-ghost">Receipt PDF</a>
            </div>
        </div>

        <div class="layout-admin-detail">
            <section class="surface-card">
                <div class="detail-list">
                    <div class="detail-row">
                        <div class="detail-label">Buyer</div>
                        <div class="detail-value">{{ $order->buyer_name }}<br><span style="color: var(--ink-muted);">{{ $order->buyer_email }}</span></div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Product</div>
                        <div class="detail-value">{{ $order->product->title }}</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Amount</div>
                        <div class="detail-value mono">${{ number_format((float) $order->amount_usd, 2) }} / {{ number_format((float) $order->crypto_amount, $order->crypto_currency === 'BTC' ? 8 : 2, '.', '') }} {{ $order->crypto_currency }}</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Coupon</div>
                        <div class="detail-value mono">{{ $order->coupon_code ?: 'None' }}</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Discount</div>
                        <div class="detail-value mono">${{ number_format((float) $order->discount_usd, 2) }}</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Rate used</div>
                        <div class="detail-value mono">${{ number_format((float) $order->crypto_rate_used, 2) }} per {{ $order->crypto_currency }}</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Submitted</div>
                        <div class="detail-value">{{ $order->created_at->format('M d, Y g:i A') }}</div>
                    </div>
                    @if ($order->approved_at)
                        <div class="detail-row">
                            <div class="detail-label">Approved</div>
                            <div class="detail-value">{{ $order->approved_at->format('M d, Y g:i A') }}</div>
                        </div>
                    @endif
                    @if ($order->download_token)
                        <div class="detail-row">
                            <div class="detail-label">Download link expires</div>
                            <div class="detail-value">{{ $order->token_expires_at?->format('M d, Y g:i A') }}</div>
                        </div>
                    @endif
                </div>
            </section>

            <section class="surface-card" style="display: grid; gap: 1.5rem;">
                <div>
                    <div class="section-head" style="margin-bottom: 1rem;">
                        <div>
                            <p class="eyebrow">Evidence</p>
                            <h2 style="font-size: 1.8rem;">Payment screenshot</h2>
                        </div>
                        <a href="{{ route('admin.orders.screenshot', $order) }}" class="button-ghost">Open original</a>
                    </div>
                    <div class="surface-card" style="padding: 0.75rem; background: var(--paper-warm);">
                        <img src="{{ route('admin.orders.screenshot', $order) }}" alt="Payment screenshot for order {{ $order->id }}" style="width: 100%; border-radius: 8px; object-fit: contain;">
                    </div>
                </div>

                <form method="POST" action="{{ route('admin.orders.approve', $order) }}" class="form-grid">
                    @csrf
                    <div>
                        <label for="notes">Notes</label>
                        <textarea id="notes" name="notes" rows="5">{{ old('notes', $order->notes) }}</textarea>
                    </div>
                    <div style="display: flex; flex-wrap: wrap; gap: 12px;">
                        <button type="submit" class="button-primary">Approve and email link</button>
                        <button type="submit" formaction="{{ route('admin.orders.reject', $order) }}" class="button-danger">Reject</button>
                        @if ($order->status === 'delivered')
                            <button type="submit" formaction="{{ route('admin.orders.refresh-download', $order) }}" class="button-ghost">Refresh link and resend</button>
                        @endif
                    </div>
                </form>
            </section>
        </div>

        <section class="surface-card" style="padding: 0; overflow: hidden;">
            <div class="section-head" style="padding: 1.75rem 1.75rem 1rem; margin-bottom: 0;">
                <div>
                    <p class="eyebrow">Logs</p>
                    <h2 style="font-size: 1.8rem;">Download attempts</h2>
                </div>
            </div>
            <div class="data-table-wrap data-table-scroll" style="border: none; border-radius: 0;">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>When</th>
                            <th>Result</th>
                            <th>Reason</th>
                            <th>IP</th>
                            <th>Agent</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($order->downloadAttempts as $attempt)
                            <tr>
                                <td>{{ $attempt->attempted_at->format('M d, Y g:i A') }}</td>
                                <td>{{ $attempt->was_successful ? 'Success' : 'Failed' }}</td>
                                <td>{{ $attempt->failure_reason ?: '-' }}</td>
                                <td class="mono">{{ $attempt->ip_address ?: '-' }}</td>
                                <td>{{ \Illuminate\Support\Str::limit($attempt->user_agent, 60) ?: '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5">No download attempts yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </div>
@endsection

