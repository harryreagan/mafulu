<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ReceiptController extends Controller
{
    public function __invoke(Request $request, Order $order)
    {
        $isAdmin = (bool) $request->session()->get(config('mafullu.admin_session_key'));

        abort_unless($isAdmin || $request->hasValidSignature(), 403);

        return Pdf::loadView('pdf.receipt', [
            'order' => $order->load(['product', 'coupon']),
        ])->stream("mafullu-receipt-{$order->id}.pdf");
    }
}

