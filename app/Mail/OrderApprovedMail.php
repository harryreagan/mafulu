<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\URL;

class OrderApprovedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Order $order) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Mafullu download is ready',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.order-approved',
            with: [
                'order' => $this->order,
                'downloadUrl' => route('download', $this->order->download_token),
                'receiptUrl' => URL::temporarySignedRoute('orders.receipt', now()->addDays(7), ['order' => $this->order]),
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}

