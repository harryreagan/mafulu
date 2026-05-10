<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewOrderSubmittedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Order $order) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Mafullu order awaiting review',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.new-order-submitted',
            with: [
                'order' => $this->order,
                'adminUrl' => route('admin.orders.show', $this->order),
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}

