@extends('layouts.app')

@section('title', 'Order received | Mafulu')

@section('content')
    <div class="surface-card" style="max-width: 760px; margin: 0 auto; display: grid; gap: 1.75rem;">
        <div class="section-head" style="margin-bottom: 0;">
            <div>
                <p class="eyebrow">Thank you</p>
                <h1 class="section-title">Your order has been received.</h1>
            </div>
            <div class="status-badge status-screenshot_uploaded">Screenshot uploaded</div>
        </div>

        <p>We have your payment screenshot for <span style="color: var(--ink); font-weight: 500;">{{ $order->product->title }}</span>. Please wait for the approval email with your download link.</p>

        <div class="detail-list">
            <div class="detail-row">
                <div class="detail-label">Order reference</div>
                <div class="detail-value mono">#{{ $order->id }}</div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Final amount</div>
                <div class="detail-value mono">${{ number_format((float) $order->amount_usd, 2) }}</div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Crypto paid</div>
                <div class="detail-value mono">{{ number_format((float) $order->crypto_amount, $order->crypto_currency === 'BTC' ? 8 : 2, '.', '') }} {{ $order->crypto_currency }}</div>
            </div>
        </div>

        <div style="display: flex; flex-wrap: wrap; gap: 12px;">
            <a href="{{ $receiptUrl }}" class="button-primary">Download receipt PDF</a>
            <a href="{{ route('store.index') }}" class="button-ghost">Back to store</a>
        </div>
    </div>
@endsection
