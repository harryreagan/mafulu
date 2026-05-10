<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class CryptoRateService
{
    public function currentRates(): array
    {
        return Cache::remember('mafullu.crypto_rates', now()->addMinutes(10), function (): array {
            $fallback = [
                Order::CRYPTO_BTC => (float) config('mafullu.rates.BTC', 85000),
                Order::CRYPTO_USDT => (float) config('mafullu.rates.USDT', 1),
            ];

            try {
                $response = Http::acceptJson()
                    ->timeout(8)
                    ->get('https://api.coingecko.com/api/v3/simple/price', [
                        'ids' => 'bitcoin,tether',
                        'vs_currencies' => 'usd',
                    ]);

                if (! $response->successful()) {
                    return $fallback;
                }

                $data = $response->json();

                return [
                    Order::CRYPTO_BTC => (float) data_get($data, 'bitcoin.usd', $fallback[Order::CRYPTO_BTC]),
                    Order::CRYPTO_USDT => (float) data_get($data, 'tether.usd', $fallback[Order::CRYPTO_USDT]),
                ];
            } catch (\Throwable $throwable) {
                return $fallback;
            }
        });
    }

    public function quote(float $usdAmount, string $currency): array
    {
        $rates = $this->currentRates();
        $rate = max((float) ($rates[$currency] ?? 1), 0.00000001);
        $precision = $currency === Order::CRYPTO_BTC ? 8 : 2;
        $amount = round($usdAmount / $rate, $precision);

        return [
            'currency' => $currency,
            'usd_amount' => round($usdAmount, 2),
            'rate_used' => $rate,
            'crypto_amount' => $amount,
            'formatted_crypto_amount' => number_format($amount, $precision, '.', ''),
        ];
    }
}

