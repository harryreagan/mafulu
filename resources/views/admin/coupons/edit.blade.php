@extends('layouts.admin')

@section('title', 'Edit Coupon | Mafulu Admin')

@section('content')
    <div class="surface-card" style="max-width: 980px;">
        <div class="section-head">
            <div>
                <p class="eyebrow">Coupons</p>
                <h1 class="section-title">Edit coupon</h1>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.coupons.update', $coupon) }}" class="form-grid">
            @csrf
            @method('PUT')
            @include('admin.coupons._form')

            <div style="display: flex; flex-wrap: wrap; gap: 12px;">
                <button type="submit" class="button-primary">Update coupon</button>
                <a href="{{ route('admin.coupons.index') }}" class="button-ghost">Cancel</a>
            </div>
        </form>
    </div>
@endsection
