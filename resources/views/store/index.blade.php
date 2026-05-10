@extends('layouts.app')

@section('title', $isHome ? 'Mafulu' : 'Store | Mafulu')

@section('content')
    @php($categories = \App\Models\Product::categories())

    <div class="section-shell">
        @if ($isHome)
            <section>
                <div class="ticker fade-up fade-up-1">
                    <div class="ticker-track">
                        <span>ebooks</span>
                        <span>templates</span>
                        <span>software</span>
                        <span>courses</span>
                        <span>private delivery</span>
                        <span>crypto checkout</span>
                        <span>manual approval</span>
                        <span>ebooks</span>
                        <span>templates</span>
                        <span>software</span>
                        <span>courses</span>
                        <span>private delivery</span>
                        <span>crypto checkout</span>
                        <span>manual approval</span>
                    </div>
                </div>

                <div class="section-shell" style="gap: 2.5rem;">
                    <div class="hero-copy fade-up fade-up-1">
                        <p class="eyebrow">Curated digital goods</p>
                        <h1 class="hero-title">Thoughtful tools for creators, freelancers, and small teams.</h1>
                        <p class="hero-text">Mafulu keeps digital buying direct and quiet: browse a compact catalog, pay in crypto, upload proof, and receive a private download link after review.</p>
                    </div>

                    <div class="fade-up fade-up-2" style="display: flex; flex-wrap: wrap; gap: 12px;">
                        <a href="{{ route('store.index') }}" class="button-primary">Browse collection</a>
                        <a href="#featured-products" class="button-ghost">See featured products</a>
                    </div>

                    <div class="metrics-grid fade-up fade-up-3">
                        <div class="surface-card interactive">
                            <p class="eyebrow">Checkout</p>
                            <h2 style="font-size: 1.8rem;">BTC & USDT</h2>
                            <p>Live quotes at checkout with private delivery once payment is approved.</p>
                        </div>
                        <div class="surface-card interactive">
                            <p class="eyebrow">Delivery</p>
                            <h2 style="font-size: 1.8rem;">Single-use links</h2>
                            <p>Receipts, protected files, and download tokens that expire automatically.</p>
                        </div>
                    </div>
                </div>
            </section>

            <section id="featured-products">
                <div class="section-head">
                    <div>
                        <p class="eyebrow">Featured products</p>
                        <h2 class="section-title">Start with something genuinely useful.</h2>
                    </div>
                    <div class="filter-row">
                        <a href="{{ route('home') }}" class="filter-pill {{ blank($selectedCategory) ? 'is-active' : '' }}">all</a>
                        @foreach ($categories as $category)
                            <a href="{{ route('home', ['category' => $category]) }}" class="filter-pill {{ $selectedCategory === $category ? 'is-active' : '' }}">{{ $category }}</a>
                        @endforeach
                    </div>
                </div>

                @if ($products->count())
                    <div class="catalog-grid">
                        @foreach ($products as $product)
                            <x-product-card :product="$product" />
                        @endforeach
                    </div>
                @else
                    <div class="surface-card">
                        <p>No products matched your filters.</p>
                    </div>
                @endif
            </section>

            <section>
                <div class="section-head">
                    <div>
                        <p class="eyebrow">Why people stay</p>
                        <h2 class="section-title">A lean flow that keeps the buying side simple.</h2>
                    </div>
                    <a href="{{ route('store.index') }}" class="button-ghost">View full store</a>
                </div>

                <div class="two-up">
                    <div class="surface-card soft">
                        <p class="eyebrow">Trust notes</p>
                        <div class="stack-list">
                            @foreach ($trustPoints as $trustPoint)
                                <p>{{ $trustPoint }}</p>
                            @endforeach
                        </div>
                    </div>

                    @if (count($testimonials))
                        <div class="stack-list">
                            @foreach ($testimonials as $testimonial)
                                <article class="surface-card interactive">
                                    <p style="font-size: 15px;">"{{ $testimonial['quote'] }}"</p>
                                    <div style="padding-top: 1rem; border-top: 1px solid var(--border);">
                                        <div style="font-size: 14px; font-weight: 500; color: var(--ink);">{{ $testimonial['author'] }}</div>
                                        <div class="mono" style="margin-top: 4px; color: var(--ink-faint); font-size: 11px; letter-spacing: 0.08em; text-transform: uppercase;">{{ $testimonial['role'] }}</div>
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    @else
                        <div class="surface-card">
                            <p class="eyebrow">Private delivery</p>
                            <p>Receipt PDFs, status tracking, and manual order review are built into the same storefront flow.</p>
                        </div>
                    @endif
                </div>
            </section>
        @else
            <section>
                <div class="section-head" style="margin-bottom: 1.75rem;">
                    <div class="store-meta" style="width: 100%;">
                        <div class="store-intro">
                            <p class="eyebrow">Store directory</p>
                            <h1 class="section-title">Browse the full Mafulu catalog.</h1>
                            <p>Search the collection, narrow it by category, and sort the listing without changing the checkout workflow.</p>
                        </div>
                        <div class="store-stat">
                            <div class="mono" style="color: var(--ink-faint); font-size: 11px; letter-spacing: 0.08em; text-transform: uppercase;">Available now</div>
                            <div style="font-family: 'DM Serif Display', serif; font-size: 2rem; color: var(--ink); line-height: 1;">{{ $products->total() }}</div>
                        </div>
                    </div>
                </div>

                <div class="toolbar-card" style="margin-bottom: 1.25rem;">
                    <div class="toolbar-head">
                        <div>
                            <div class="toolbar-note">Search, refine, sort</div>
                            <p style="margin-top: 0.4rem; color: var(--ink-muted); font-size: 14px;">A cleaner way to navigate the catalog.</p>
                        </div>
                        <div class="toolbar-note">{{ $products->total() }} results in catalog</div>
                    </div>

                    <form method="GET" action="{{ route('store.index') }}" class="toolbar-grid">
                        <div class="toolbar-search">
                            <label for="search">Search</label>
                            <input id="search" type="text" name="search" value="{{ $search }}" placeholder="Search products, topics, or formats">
                        </div>
                        <div>
                            <label for="category">Category</label>
                            <select id="category" name="category">
                                <option value="">All categories</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category }}" @selected($selectedCategory === $category)>{{ ucfirst($category) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="sort">Sort</label>
                            <select id="sort" name="sort">
                                <option value="latest" @selected($sort === 'latest')>Latest</option>
                                <option value="popular" @selected($sort === 'popular')>Most popular</option>
                                <option value="price_low" @selected($sort === 'price_low')>Price: low to high</option>
                                <option value="price_high" @selected($sort === 'price_high')>Price: high to low</option>
                            </select>
                        </div>
                        <div class="toolbar-action">
                            <button type="submit" class="button-primary">Apply filters</button>
                        </div>
                    </form>
                </div>

                <div class="filter-row" style="margin-bottom: 1.5rem; align-items: center; justify-content: space-between;">
                    <div class="filter-row">
                        <a href="{{ route('store.index', array_filter(['search' => $search ?: null, 'sort' => $sort !== 'latest' ? $sort : null])) }}" class="filter-pill {{ blank($selectedCategory) ? 'is-active' : '' }}">all</a>
                        @foreach ($categories as $category)
                            <a href="{{ route('store.index', array_filter(['category' => $category, 'search' => $search ?: null, 'sort' => $sort !== 'latest' ? $sort : null])) }}" class="filter-pill {{ $selectedCategory === $category ? 'is-active' : '' }}">{{ $category }}</a>
                        @endforeach
                    </div>
                    <div class="toolbar-note">{{ $products->count() }} shown on this page</div>
                </div>

                @if ($products->count())
                    <div class="catalog-grid">
                        @foreach ($products as $product)
                            <x-product-card :product="$product" />
                        @endforeach
                    </div>
                @else
                    <div class="surface-card">
                        <p>No products matched your filters.</p>
                    </div>
                @endif

                <div class="pagination-shell">
                    {{ $products->links() }}
                </div>
            </section>
        @endif
    </div>
@endsection
