@php
    $statusClasses = [
        'pending' => 'status-badge status-pending',
        'screenshot_uploaded' => 'status-badge status-screenshot_uploaded',
        'confirmed' => 'status-badge status-confirmed',
        'delivered' => 'status-badge status-delivered',
    ];
@endphp

@extends('layouts.admin')

@section('title', 'Orders | Mafulu Admin')

@section('content')
    <div style="display: grid; gap: 2rem;">
        <div class="section-head">
            <div>
                <p class="eyebrow">Orders</p>
                <h1 class="section-title">Review incoming payments</h1>
            </div>

            <form method="GET" action="{{ route('admin.orders.index') }}" style="display: flex; flex-wrap: wrap; align-items: end; gap: 12px;">
                <div style="min-width: 220px;">
                    <label for="status">Status</label>
                    <select id="status" name="status">
                        <option value="">All statuses</option>
                        @foreach ($statuses as $status)
                            <option value="{{ $status }}" @selected($selectedStatus === $status)>{{ ucfirst(str_replace('_', ' ', $status)) }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="button-primary">Filter</button>
            </form>
        </div>

        <section class="surface-card" style="padding: 0; overflow: hidden;">
            <div class="data-table-wrap data-table-scroll" style="border: none; border-radius: 0;">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Order</th>
                            <th>Buyer</th>
                            <th>Product</th>
                            <th>Amount</th>
                            <th>Coupon</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($orders as $order)
                            <tr>
                                <td class="mono">#{{ $order->id }}</td>
                                <td>
                                    <div style="font-weight: 500; color: var(--ink);">{{ $order->buyer_name }}</div>
                                    <div style="font-size: 13px; color: var(--ink-muted);">{{ $order->buyer_email }}</div>
                                </td>
                                <td>{{ $order->product->title }}</td>
                                <td class="mono">${{ number_format((float) $order->amount_usd, 2) }} / {{ $order->crypto_currency }}</td>
                                <td class="mono">{{ $order->coupon_code ?: '-' }}</td>
                                <td>
                                    <span class="{{ $statusClasses[$order->status] ?? 'status-badge' }}">{{ str_replace('_', ' ', $order->status) }}</span>
                                </td>
                                <td><a href="{{ route('admin.orders.show', $order) }}" class="button-ghost" style="padding: 7px 12px;">View</a></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7">No orders found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <div>
            {{ $orders->links() }}
        </div>
    </div>
@endsection
