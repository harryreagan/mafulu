@extends('layouts.app')

@section('title', 'Checkout | ' . $product->title)

@section('content')
    @php
        $walletOptions = [
            'BTC' => $wallets['BTC'] ?: 'Set BTC_ADDRESS in .env',
            'USDT' => $wallets['USDT'] ?: 'Set USDT_ADDRESS in .env',
        ];
        $btcQr = \SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')->size(176)->margin(1)->generate($walletOptions['BTC']);
        $usdtQr = \SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')->size(176)->margin(1)->generate($walletOptions['USDT']);
    @endphp

    <div x-data="{
            selectedCurrency: @js(old('crypto_currency', 'BTC')),
            wallets: @js($walletOptions),
            quotes: @js($quotes),
            copied: false,
            quote() {
                return this.quotes[this.selectedCurrency];
            },
            copyAddress() {
                navigator.clipboard.writeText(this.wallets[this.selectedCurrency]);
                this.copied = true;
                setTimeout(() => this.copied = false, 1600);
            }
        }" class="layout-split-wide">
        <section class="surface-card" style="display: grid; gap: 1.5rem;">
            <div class="section-head" style="margin-bottom: 0;">
                <div>
                    <p class="eyebrow">Order summary</p>
                    <h1 class="section-title">{{ $product->title }}</h1>
                </div>
                <x-badge :value="$product->category" />
            </div>

            <form method="GET" action="{{ route('checkout.show', $product) }}" class="surface-card soft" style="display: grid; gap: 0.85rem; padding: 1.25rem;">
                <div>
                    <label for="coupon">Coupon code</label>
                    <input id="coupon" type="text" name="coupon" value="{{ request('coupon', $pricing['coupon_code']) }}" placeholder="Enter code">
                </div>
                <div style="display: flex; justify-content: flex-end;">
                    <button type="submit" class="button-ghost">Apply coupon</button>
                </div>
                @if ($pricing['coupon'])
                    <p style="color: var(--teal); font-weight: 400;">Coupon {{ $pricing['coupon']->code }} applied.</p>
                @elseif (filled($pricing['coupon_code']))
                    <p style="color: #a32d2d; font-weight: 400;">That coupon is invalid or expired.</p>
                @endif
            </form>

            <div class="detail-list">
                <div class="detail-row">
                    <div class="detail-label">Product price</div>
                    <div class="detail-value mono">${{ number_format((float) $product->price_usd, 2) }}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Discount</div>
                    <div class="detail-value mono">-${{ number_format((float) $pricing['discount_usd'], 2) }}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Total in USD</div>
                    <div class="detail-value mono">${{ number_format((float) $pricing['final_amount_usd'], 2) }}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Equivalent in crypto</div>
                    <div class="detail-value mono" x-text="quote().formatted_crypto_amount + ' ' + quote().currency"></div>
                </div>
            </div>

            <div class="surface-card soft" style="padding: 1.25rem;">
                <p>Quotes try to refresh from live market data and fall back to your configured rates if the API is unavailable.</p>
            </div>
        </section>

        <section class="surface-card">
            <form method="POST" action="{{ route('checkout.store', $product) }}" enctype="multipart/form-data" class="form-grid">
                @csrf
                <input type="hidden" name="crypto_currency" :value="selectedCurrency">
                <input type="hidden" name="coupon_code" value="{{ $pricing['coupon_code'] }}">

                <div>
                    <p class="eyebrow">Payment</p>
                    <div class="two-up" style="gap: 0.75rem;">
                        <button type="button" @click="selectedCurrency = 'BTC'" :style="selectedCurrency === 'BTC' ? 'background: var(--ink); color: var(--paper); border-color: var(--ink);' : 'background: transparent; color: var(--ink-muted); border-color: var(--border-strong);'" class="button-ghost">BTC</button>
                        <button type="button" @click="selectedCurrency = 'USDT'" :style="selectedCurrency === 'USDT' ? 'background: var(--ink); color: var(--paper); border-color: var(--ink);' : 'background: transparent; color: var(--ink-muted); border-color: var(--border-strong);'" class="button-ghost">USDT</button>
                    </div>
                </div>

                <div class="form-grid">
                    <div>
                        <label>Wallet address</label>
                        <div class="wallet-box" x-text="wallets[selectedCurrency]"></div>
                    </div>
                    <div style="display: flex; justify-content: flex-end;">
                        <button type="button" @click="copyAddress()" class="button-ghost">
                            <span x-show="! copied">Copy address</span>
                            <span x-show="copied">Copied</span>
                        </button>
                    </div>
                </div>

                <div class="two-up" style="align-items: center; gap: 1rem;">
                    <div class="surface-card soft" style="padding: 1rem; display: flex; justify-content: center;">
                        <div x-show="selectedCurrency === 'BTC'">{!! $btcQr !!}</div>
                        <div x-show="selectedCurrency === 'USDT'">{!! $usdtQr !!}</div>
                    </div>
                    <div class="detail-list">
                        <div class="detail-row">
                            <div class="detail-label">Amount to send</div>
                            <div class="detail-value mono" x-text="quote().formatted_crypto_amount + ' ' + quote().currency"></div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Rate</div>
                            <div class="detail-value mono">Stored on your receipt</div>
                        </div>
                    </div>
                </div>

                <div class="two-up">
                    <div>
                        <label for="buyer_name">Your name</label>
                        <input id="buyer_name" type="text" name="buyer_name" value="{{ old('buyer_name', auth()->user()?->name) }}">
                    </div>
                    <div>
                        <label for="buyer_email">Email address</label>
                        <input id="buyer_email" type="email" name="buyer_email" value="{{ old('buyer_email', auth()->user()?->email) }}">
                    </div>
                </div>

                <div class="upload-field">
                    <label for="payment_screenshot">Payment screenshot</label>
                    <input id="payment_screenshot" type="file" name="payment_screenshot" accept="image/*">
                </div>

                <button type="submit" class="button-primary" style="width: 100%;">I have paid - submit screenshot</button>
            </form>
        </section>
    </div>
@endsection

