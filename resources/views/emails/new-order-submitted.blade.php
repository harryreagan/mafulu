<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>New Mafulu order awaiting review</title>
    </head>
    <body style="margin:0;background:#fafafc;color:#0f172a;font-family:Inter,Arial,sans-serif;">
        <div style="max-width:640px;margin:0 auto;padding:40px 20px;">
            <div style="border:1px solid #f1f5f9;background:#ffffff;border-radius:28px;padding:32px;">
                <p style="margin:0 0 12px;color:#534AB7;font-size:13px;font-weight:600;letter-spacing:0.12em;text-transform:uppercase;">Mafulu Admin</p>
                <h1 style="margin:0 0 16px;font-size:28px;font-weight:500;line-height:1.2;">A new order needs review.</h1>
                <p style="margin:0 0 12px;font-size:15px;line-height:1.7;color:#475569;">Order #{{ $order->id }} was submitted by {{ $order->buyer_name }} for {{ $order->product->title }}.</p>
                <p style="margin:0 0 24px;font-size:15px;line-height:1.7;color:#475569;">Amount: ${{ number_format((float) $order->amount_usd, 2) }} / {{ number_format((float) $order->crypto_amount, $order->crypto_currency === 'BTC' ? 8 : 2, '.', '') }} {{ $order->crypto_currency }}</p>
                <p style="margin:0;">
                    <a href="{{ $adminUrl }}" style="display:inline-block;border-radius:9999px;background:#534AB7;color:#ffffff;padding:14px 22px;font-size:14px;font-weight:600;text-decoration:none;">Review order</a>
                </p>
            </div>
        </div>
    </body>
</html>


