@extends('layouts.admin')

@section('title', 'Products | Mafulu Admin')

@section('content')
    <div style="display: grid; gap: 2rem;">
        <div class="section-head">
            <div>
                <p class="eyebrow">Products</p>
                <h1 class="section-title">Manage your catalog</h1>
            </div>
            <a href="{{ route('admin.products.create') }}" class="button-primary">Add product</a>
        </div>

        <section class="surface-card" style="padding: 0; overflow: hidden;">
            <div class="data-table-wrap data-table-scroll" style="border: none; border-radius: 0;">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th>Preview</th>
                            <th>Status</th>
                            <th>Sales</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($products as $product)
                            <tr>
                                <td style="font-weight: 500;">{{ $product->title }}</td>
                                <td><x-badge :value="$product->category" /></td>
                                <td class="mono">${{ number_format((float) $product->price_usd, 2) }}</td>
                                <td>{{ $product->preview_path ? 'Yes' : 'No' }}</td>
                                <td>{{ $product->is_active ? 'Active' : 'Inactive' }}</td>
                                <td class="mono">{{ $product->sales_count }}</td>
                                <td>
                                    <div style="display: flex; gap: 10px; justify-content: flex-end;">
                                        <a href="{{ route('admin.products.edit', $product) }}" class="button-ghost" style="padding: 7px 12px;">Edit</a>
                                        <form method="POST" action="{{ route('admin.products.destroy', $product) }}" onsubmit="return confirm('Delete this product?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="button-danger" style="padding: 7px 12px;">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7">No products added yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <div>
            {{ $products->links() }}
        </div>
    </div>
@endsection
