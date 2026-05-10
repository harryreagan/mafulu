<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Contracts\View\View;

class StoreController extends Controller
{
    public function home(): View
    {
        $selectedCategory = request('category');

        $this->loadBuyerWishlist();

        $products = Product::query()
            ->active()
            ->when(
                in_array($selectedCategory, Product::categories(), true),
                fn ($query) => $query->where('category', $selectedCategory)
            )
            ->latest()
            ->take(6)
            ->get();

        return view('store.index', [
            'isHome' => true,
            'products' => $products,
            'selectedCategory' => $selectedCategory,
            'search' => null,
            'sort' => 'latest',
            'testimonials' => config('mafullu.testimonials', []),
            'trustPoints' => config('mafullu.trust_points', []),
        ]);
    }

    public function index(): View
    {
        $selectedCategory = request('category');
        $search = trim((string) request('search'));
        $sort = request('sort', 'latest');

        $this->loadBuyerWishlist();

        $products = Product::query()
            ->active()
            ->when(
                in_array($selectedCategory, Product::categories(), true),
                fn ($query) => $query->where('category', $selectedCategory)
            )
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($innerQuery) use ($search) {
                    $innerQuery
                        ->where('title', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            });

        match ($sort) {
            'price_low' => $products->orderBy('price_usd'),
            'price_high' => $products->orderByDesc('price_usd'),
            'popular' => $products->orderByDesc('sales_count')->latest(),
            default => $products->latest(),
        };

        return view('store.index', [
            'isHome' => false,
            'products' => $products->paginate(12)->withQueryString(),
            'selectedCategory' => $selectedCategory,
            'search' => $search,
            'sort' => $sort,
            'testimonials' => [],
            'trustPoints' => [],
        ]);
    }

    public function show(Product $product): View
    {
        abort_unless($product->is_active, 404);

        $this->loadBuyerWishlist();

        return view('product.show', [
            'product' => $product,
        ]);
    }

    protected function loadBuyerWishlist(): void
    {
        auth()->user()?->loadMissing('wishlists');
    }
}
