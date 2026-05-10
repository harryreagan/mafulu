<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\URL;

class OrderRejectedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Order $order) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Mafullu payment needs attention',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.order-rejected',
            with: [
                'order' => $this->order,
                'checkoutUrl' => route('checkout.show', $this->order->product),
                'receiptUrl' => URL::temporarySignedRoute('orders.receipt', now()->addDays(7), ['order' => $this->order]),
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}

