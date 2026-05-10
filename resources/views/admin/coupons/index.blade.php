@extends('layouts.admin')

@section('title', 'Coupons | Mafulu Admin')

@section('content')
    <div style="display: grid; gap: 2rem;">
        <div class="section-head">
            <div>
                <p class="eyebrow">Coupons</p>
                <h1 class="section-title">Manage discounts</h1>
            </div>
            <a href="{{ route('admin.coupons.create') }}" class="button-primary">Add coupon</a>
        </div>

        <section class="surface-card" style="padding: 0; overflow: hidden;">
            <div class="data-table-wrap data-table-scroll" style="border: none; border-radius: 0;">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Code</th>
                            <th>Type</th>
                            <th>Value</th>
                            <th>Usage</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($coupons as $coupon)
                            <tr>
                                <td class="mono" style="font-weight: 500;">{{ $coupon->code }}</td>
                                <td>{{ ucfirst($coupon->type) }}</td>
                                <td class="mono">{{ $coupon->type === 'percentage' ? rtrim(rtrim(number_format((float) $coupon->value, 2), '0'), '.') . '%' : '$' . number_format((float) $coupon->value, 2) }}</td>
                                <td class="mono">{{ $coupon->times_redeemed }}{{ $coupon->usage_limit ? ' / ' . $coupon->usage_limit : '' }}</td>
                                <td>{{ $coupon->is_active ? 'Active' : 'Inactive' }}</td>
                                <td>
                                    <div style="display: flex; gap: 10px; justify-content: flex-end;">
                                        <a href="{{ route('admin.coupons.edit', $coupon) }}" class="button-ghost" style="padding: 7px 12px;">Edit</a>
                                        <form method="POST" action="{{ route('admin.coupons.destroy', $coupon) }}" onsubmit="return confirm('Delete this coupon?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="button-danger" style="padding: 7px 12px;">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6">No coupons created yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <div>
            {{ $coupons->links() }}
        </div>
    </div>
@endsection
