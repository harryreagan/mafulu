@extends('layouts.admin')

@section('title', 'Create Coupon | Mafulu Admin')

@section('content')
    <div class="surface-card" style="max-width: 980px;">
        <div class="section-head">
            <div>
                <p class="eyebrow">Coupons</p>
                <h1 class="section-title">Create coupon</h1>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.coupons.store') }}" class="form-grid">
            @csrf
            @include('admin.coupons._form')

            <div style="display: flex; flex-wrap: wrap; gap: 12px;">
                <button type="submit" class="button-primary">Save coupon</button>
                <a href="{{ route('admin.coupons.index') }}" class="button-ghost">Cancel</a>
            </div>
        </form>
    </div>
@endsection
