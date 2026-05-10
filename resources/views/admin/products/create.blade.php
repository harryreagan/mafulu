@extends('layouts.admin')

@section('title', 'Create Product | Mafulu Admin')

@section('content')
    <div class="surface-card" style="max-width: 980px;">
        <div class="section-head">
            <div>
                <p class="eyebrow">Products</p>
                <h1 class="section-title">Create product</h1>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data" class="form-grid">
            @csrf
            @include('admin.products._form')

            <div style="display: flex; flex-wrap: wrap; gap: 12px;">
                <button type="submit" class="button-primary">Save product</button>
                <a href="{{ route('admin.products.index') }}" class="button-ghost">Cancel</a>
            </div>
        </form>
    </div>
@endsection
