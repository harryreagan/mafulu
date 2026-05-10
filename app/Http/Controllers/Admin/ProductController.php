<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(): View
    {
        return view('admin.products.index', [
            'products' => Product::query()->latest()->paginate(12),
        ]);
    }

    public function create(): View
    {
        return view('admin.products.create', [
            'product' => new Product(),
            'categories' => Product::categories(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateProduct($request);

        Product::create([
            'title' => $validated['title'],
            'slug' => $this->uniqueSlug($validated['title']),
            'description' => $validated['description'],
            'category' => $validated['category'],
            'price_usd' => $validated['price_usd'],
            'file_path' => $request->file('product_file')->store('products', 'local'),
            'preview_path' => $request->file('preview_file')?->store('product-previews', 'local'),
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('admin.products.index')->with('status', 'Product created.');
    }

    public function edit(Product $product): View
    {
        return view('admin.products.edit', [
            'product' => $product,
            'categories' => Product::categories(),
        ]);
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $validated = $this->validateProduct($request, $product);

        $attributes = [
            'title' => $validated['title'],
            'slug' => $this->uniqueSlug($validated['title'], $product),
            'description' => $validated['description'],
            'category' => $validated['category'],
            'price_usd' => $validated['price_usd'],
            'is_active' => $request->boolean('is_active'),
        ];

        if ($request->hasFile('product_file')) {
            Storage::disk('local')->delete($product->file_path);
            $attributes['file_path'] = $request->file('product_file')->store('products', 'local');
        }

        if ($request->hasFile('preview_file')) {
            if ($product->preview_path) {
                Storage::disk('local')->delete($product->preview_path);
            }

            $attributes['preview_path'] = $request->file('preview_file')->store('product-previews', 'local');
        }

        $product->update($attributes);

        return redirect()->route('admin.products.index')->with('status', 'Product updated.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        if ($product->orders()->exists()) {
            return redirect()->route('admin.products.index')->withErrors(['product' => 'This product has orders and cannot be deleted.']);
        }

        Storage::disk('local')->delete(array_filter([$product->file_path, $product->preview_path]));
        $product->delete();

        return redirect()->route('admin.products.index')->with('status', 'Product deleted.');
    }

    protected function validateProduct(Request $request, ?Product $product = null): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'category' => ['required', 'in:'.implode(',', Product::categories())],
            'price_usd' => ['required', 'numeric', 'min:0.01'],
            'product_file' => [$product ? 'nullable' : 'required', 'file', 'max:51200'],
            'preview_file' => ['nullable', 'file', 'max:20480'],
            'is_active' => ['nullable', 'boolean'],
        ]);
    }

    protected function uniqueSlug(string $title, ?Product $product = null): string
    {
        $baseSlug = Str::slug($title);
        $slug = $baseSlug;
        $counter = 2;

        while (
            Product::query()
                ->where('slug', $slug)
                ->when($product, fn ($query) => $query->whereKeyNot($product->id))
                ->exists()
        ) {
            $slug = "{$baseSlug}-{$counter}";
            $counter++;
        }

        return $slug;
    }
}
