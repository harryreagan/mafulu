@extends('layouts.app')

@section('title', $product->title . ' | Mafulu')

@section('content')
    @php
        $iconMap = [
            'ebook' => 'EB',
            'template' => 'TP',
            'software' => 'SW',
            'course' => 'CR',
        ];
        $saved = auth()->check() ? auth()->user()->wishlists->contains('product_id', $product->id) : false;
    @endphp

    <div class="layout-split-product">
        <section class="surface-card">
            <div class="section-head" style="margin-bottom: 1.75rem;">
                <div>
                    <p class="eyebrow">Product detail</p>
                    <h1 class="section-title">{{ $product->title }}</h1>
                </div>
                <x-badge :value="$product->category" />
            </div>

            <div style="display: grid; gap: 1.5rem;">
                <div class="category-icon icon-{{ $product->category }}" style="width: 64px; height: 64px; font-size: 15px;">{{ $iconMap[$product->category] ?? 'DP' }}</div>
                <p style="font-size: 15px; max-width: 760px;">{!! nl2br(e($product->description)) !!}</p>
                @if ($product->hasPreview())
                    <div>
                        <a href="{{ route('products.preview', $product) }}" target="_blank" class="button-ghost">Open preview</a>
                    </div>
                @endif
            </div>
        </section>

        <aside class="surface-card sidebar-sticky">
            <div style="display: grid; gap: 1.5rem;">
                <div>
                    <p class="eyebrow">Price</p>
                    <div class="price-display" style="font-size: 2.4rem;">${{ number_format((float) $product->price_usd, 2) }}</div>
                    <div class="price-note">Live crypto conversion shown during checkout</div>
                </div>

                <div class="surface-card soft" style="padding: 1.25rem; display: grid; gap: 0.75rem;">
                    <p>Every purchase keeps the same workflow: pay in crypto, upload your screenshot, then wait for the approval email with a private download link.</p>
                    <p class="mono" style="font-size: 11px; color: var(--ink-faint); text-transform: uppercase; letter-spacing: 0.08em;">Login is required before checkout so buyers can track orders, downloads, and support updates.</p>
                </div>

                <div style="display: grid; gap: 0.75rem;">
                    <a href="{{ route('checkout.show', $product) }}" class="button-primary" style="width: 100%;">{{ auth()->check() ? 'Buy with crypto' : 'Login to buy' }}</a>
                    @auth
                        <form method="POST" action="{{ route('wishlist.toggle', $product) }}">
                            @csrf
                            <button type="submit" class="button-ghost" style="width: 100%;">{{ $saved ? 'Saved to dashboard' : 'Save for later' }}</button>
                        </form>
                    @endauth
                    @if ($product->hasPreview())
                        <a href="{{ route('products.preview', $product) }}" target="_blank" class="button-ghost" style="width: 100%;">Preview sample file</a>
                    @endif
                </div>
            </div>
        </aside>
    </div>
@endsection
