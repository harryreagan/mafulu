<?php

namespace App\Services;

use App\Models\Coupon;

class CouponService
{
    public function findActive(?string $code): ?Coupon
    {
        $normalizedCode = $this->normalizeCode($code);

        if ($normalizedCode === null) {
            return null;
        }

        return Coupon::query()
            ->active()
            ->where('code', $normalizedCode)
            ->first();
    }

    public function apply(?string $code, float $baseAmount): array
    {
        $coupon = $this->findActive($code);
        $normalizedCode = $this->normalizeCode($code);
        $discountUsd = $coupon ? $this->discountFor($coupon, $baseAmount) : 0;

        return [
            'coupon' => $coupon,
            'coupon_code' => $coupon?->code ?? $normalizedCode,
            'discount_usd' => round($discountUsd, 2),
            'final_amount_usd' => round(max($baseAmount - $discountUsd, 0.01), 2),
        ];
    }

    public function discountFor(Coupon $coupon, float $baseAmount): float
    {
        if ($coupon->type === Coupon::TYPE_PERCENTAGE) {
            return round($baseAmount * (min((float) $coupon->value, 100) / 100), 2);
        }

        return round(min((float) $coupon->value, $baseAmount), 2);
    }

    public function redeem(?Coupon $coupon): void
    {
        if ($coupon) {
            $coupon->increment('times_redeemed');
        }
    }

    public function normalizeCode(?string $code): ?string
    {
        $normalized = strtoupper(trim((string) $code));

        return $normalized !== '' ? $normalized : null;
    }
}
