<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Your Mafulu download is ready</title>
    </head>
    <body style="margin:0;background:#fafafc;color:#0f172a;font-family:Inter,Arial,sans-serif;">
        <div style="max-width:640px;margin:0 auto;padding:40px 20px;">
            <div style="border:1px solid #f1f5f9;background:#ffffff;border-radius:28px;padding:32px;">
                <p style="margin:0 0 12px;color:#534AB7;font-size:13px;font-weight:600;letter-spacing:0.12em;text-transform:uppercase;">Mafulu</p>
                <h1 style="margin:0 0 16px;font-size:28px;font-weight:500;line-height:1.2;">Your download is ready.</h1>
                <p style="margin:0 0 24px;font-size:15px;line-height:1.7;color:#475569;">Hi {{ $order->buyer_name }}, your payment for {{ $order->product->title }} has been approved. Use the secure link below within 48 hours. The link works once.</p>
                <p style="margin:0 0 18px;">
                    <a href="{{ $downloadUrl }}" style="display:inline-block;border-radius:9999px;background:#534AB7;color:#ffffff;padding:14px 22px;font-size:14px;font-weight:600;text-decoration:none;">Download your file</a>
                </p>
                <p style="margin:0 0 28px;">
                    <a href="{{ $receiptUrl }}" style="display:inline-block;border-radius:9999px;border:1px solid #e2e8f0;color:#334155;padding:14px 22px;font-size:14px;font-weight:600;text-decoration:none;">Receipt PDF</a>
                </p>
                <p style="margin:0;font-size:13px;line-height:1.7;color:#64748b;">Order reference: #{{ $order->id }}</p>
            </div>
        </div>
    </body>
</html>


