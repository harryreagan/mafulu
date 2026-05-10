<div class="form-grid">
    <div>
        <label for="code">Coupon code</label>
        <input id="code" type="text" name="code" value="{{ old('code', $coupon->code) }}">
    </div>

    <div class="two-up">
        <div>
            <label for="type">Discount type</label>
            <select id="type" name="type">
                @foreach ($types as $type)
                    <option value="{{ $type }}" @selected(old('type', $coupon->type) === $type)>{{ ucfirst($type) }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label for="value">Value</label>
            <input id="value" type="number" step="0.01" min="0.01" name="value" value="{{ old('value', $coupon->value) }}">
        </div>
    </div>

    <div class="two-up">
        <div>
            <label for="usage_limit">Usage limit</label>
            <input id="usage_limit" type="number" min="1" name="usage_limit" value="{{ old('usage_limit', $coupon->usage_limit) }}">
        </div>
        <div>
            <label for="expires_at">Expires at</label>
            <input id="expires_at" type="datetime-local" name="expires_at" value="{{ old('expires_at', $coupon->expires_at?->format('Y-m-d\TH:i')) }}">
        </div>
    </div>

    @if ($coupon->exists)
        <div class="surface-card" style="padding: 1rem 1.1rem; background: var(--paper-warm);">
            <p>Redeemed {{ $coupon->times_redeemed }} times.</p>
        </div>
    @endif

    <label style="display: flex; align-items: center; gap: 10px; margin: 0;">
        <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $coupon->exists ? $coupon->is_active : true)) style="width: 16px; height: 16px; margin: 0;">
        <span style="font-size: 14px; color: var(--ink-muted);">Coupon is active and can be redeemed</span>
    </label>
</div>

