@props(['product', 'saved' => null])

@php
    $iconMap = [
        'ebook' => 'EB',
        'template' => 'TP',
        'software' => 'SW',
        'course' => 'CR',
    ];

    $resolvedSaved = $saved;

    if ($resolvedSaved === null && auth()->check()) {
        $resolvedSaved = auth()->user()->wishlists->contains('product_id', $product->id);
    }
@endphp

<article class="product-card">
    <div class="product-card-top">
        <div class="category-icon icon-{{ $product->category }}">{{ $iconMap[$product->category] ?? 'DP' }}</div>
        <div class="product-card-tags">
            @if ($product->hasPreview())
                <span class="badge" style="background: var(--paper-warm); color: var(--ink-muted);">Preview</span>
            @endif
            <x-badge :value="$product->category" />
        </div>
    </div>

    <div class="product-card-copy">
        <h3 class="product-card-title">
            <a href="{{ route('products.show', $product) }}">{{ $product->title }}</a>
        </h3>
        <p class="product-card-text">{{ \Illuminate\Support\Str::limit(strip_tags($product->description), 160) }}</p>
    </div>

    <div class="product-card-foot">
        <div>
            <div class="price-display">${{ number_format((float) $product->price_usd, 2) }}</div>
            <div class="price-note">BTC / USDT quoted at checkout</div>
        </div>

        <div style="display: grid; gap: 0.55rem; justify-items: end;">
            @auth
                <form method="POST" action="{{ route('wishlist.toggle', $product) }}">
                    @csrf
                    <button type="submit" class="button-ghost" style="padding: 7px 12px; min-width: 92px;">
                        {{ $resolvedSaved ? 'Saved' : 'Save' }}
                    </button>
                </form>
            @endauth

            <a href="{{ route('checkout.show', $product) }}" class="button-primary" style="padding: 8px 14px; min-width: 112px;">
                {{ auth()->check() ? 'Buy now' : 'Login to buy' }}
            </a>
        </div>
    </div>
</article>
