<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CouponController extends Controller
{
    public function index(): View
    {
        return view('admin.coupons.index', [
            'coupons' => Coupon::query()->latest()->paginate(15),
        ]);
    }

    public function create(): View
    {
        return view('admin.coupons.create', [
            'coupon' => new Coupon(),
            'types' => Coupon::types(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateCoupon($request);
        $validated['code'] = strtoupper($validated['code']);

        Coupon::create($validated + [
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('admin.coupons.index')->with('status', 'Coupon created.');
    }

    public function edit(Coupon $coupon): View
    {
        return view('admin.coupons.edit', [
            'coupon' => $coupon,
            'types' => Coupon::types(),
        ]);
    }

    public function update(Request $request, Coupon $coupon): RedirectResponse
    {
        $validated = $this->validateCoupon($request, $coupon);
        $validated['code'] = strtoupper($validated['code']);

        $coupon->update($validated + [
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('admin.coupons.index')->with('status', 'Coupon updated.');
    }

    public function destroy(Coupon $coupon): RedirectResponse
    {
        $coupon->delete();

        return redirect()->route('admin.coupons.index')->with('status', 'Coupon deleted.');
    }

    protected function validateCoupon(Request $request, ?Coupon $coupon = null): array
    {
        return $request->validate([
            'code' => ['required', 'string', 'max:50', Rule::unique('coupons', 'code')->ignore($coupon?->id)],
            'type' => ['required', 'in:'.implode(',', Coupon::types())],
            'value' => ['required', 'numeric', 'min:0.01'],
            'usage_limit' => ['nullable', 'integer', 'min:1'],
            'expires_at' => ['nullable', 'date'],
        ]);
    }
}
