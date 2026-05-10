@extends('layouts.admin')

@section('title', 'Edit Product | Mafulu Admin')

@section('content')
    <div class="surface-card" style="max-width: 980px;">
        <div class="section-head">
            <div>
                <p class="eyebrow">Products</p>
                <h1 class="section-title">Edit product</h1>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.products.update', $product) }}" enctype="multipart/form-data" class="form-grid">
            @csrf
            @method('PUT')
            @include('admin.products._form')

            <div style="display: flex; flex-wrap: wrap; gap: 12px;">
                <button type="submit" class="button-primary">Update product</button>
                <a href="{{ route('admin.products.index') }}" class="button-ghost">Cancel</a>
            </div>
        </form>
    </div>
@endsection
