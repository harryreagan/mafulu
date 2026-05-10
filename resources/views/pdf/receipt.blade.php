<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Mafulu Receipt #{{ $order->id }}</title>
    </head>
    <body style="font-family: DejaVu Sans, sans-serif; color: #0f172a; font-size: 12px; line-height: 1.6;">
        <div style="border: 1px solid #e2e8f0; border-radius: 18px; padding: 28px;">
            <table width="100%" cellpadding="0" cellspacing="0">
                <tr>
                    <td>
                        <p style="margin:0 0 8px; color:#534AB7; font-size:11px; letter-spacing:0.12em; text-transform:uppercase;">Mafulu</p>
                        <h1 style="margin:0; font-size:26px; font-weight:500;">Receipt</h1>
                    </td>
                    <td align="right">
                        <p style="margin:0; color:#64748b;">Order #{{ $order->id }}</p>
                        <p style="margin:0; color:#64748b;">{{ $order->created_at->format('M d, Y') }}</p>
                    </td>
                </tr>
            </table>

            <div style="margin-top:28px;">
                <p style="margin:0; color:#64748b;">Buyer</p>
                <p style="margin:4px 0 0; font-size:14px;">{{ $order->buyer_name }}</p>
                <p style="margin:0; color:#475569;">{{ $order->buyer_email }}</p>
            </div>

            <table width="100%" cellpadding="0" cellspacing="0" style="margin-top:28px; border-collapse:collapse;">
                <thead>
                    <tr>
                        <th align="left" style="padding:12px 0; border-bottom:1px solid #e2e8f0; color:#64748b; font-weight:500;">Item</th>
                        <th align="left" style="padding:12px 0; border-bottom:1px solid #e2e8f0; color:#64748b; font-weight:500;">Coupon</th>
                        <th align="right" style="padding:12px 0; border-bottom:1px solid #e2e8f0; color:#64748b; font-weight:500;">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="padding:14px 0; border-bottom:1px solid #f1f5f9;">{{ $order->product->title }}</td>
                        <td style="padding:14px 0; border-bottom:1px solid #f1f5f9;">{{ $order->coupon_code ?: '-' }}</td>
                        <td align="right" style="padding:14px 0; border-bottom:1px solid #f1f5f9;">${{ number_format((float) ($order->amount_usd + $order->discount_usd), 2) }}</td>
                    </tr>
                    <tr>
                        <td style="padding:14px 0;">Discount</td>
                        <td></td>
                        <td align="right" style="padding:14px 0;">-${{ number_format((float) $order->discount_usd, 2) }}</td>
                    </tr>
                    <tr>
                        <td style="padding:14px 0; font-weight:700;">Total</td>
                        <td></td>
                        <td align="right" style="padding:14px 0; font-weight:700;">${{ number_format((float) $order->amount_usd, 2) }}</td>
                    </tr>
                </tbody>
            </table>

            <div style="margin-top:24px;">
                <p style="margin:0; color:#64748b;">Crypto payment</p>
                <p style="margin:4px 0 0;">{{ number_format((float) $order->crypto_amount, $order->crypto_currency === 'BTC' ? 8 : 2, '.', '') }} {{ $order->crypto_currency }} at ${{ number_format((float) $order->crypto_rate_used, 2) }} per {{ $order->crypto_currency }}</p>
            </div>
        </div>
    </body>
</html>


