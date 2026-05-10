<?php

namespace App\Services;

use App\Models\BuyerNotification;
use App\Models\BuyerSupportRequest;
use App\Models\Order;
use App\Models\User;

class BuyerActivityService
{
    public function orderSubmitted(Order $order): void
    {
        $this->addOrderUpdate(
            $order,
            'submitted',
            'Payment screenshot uploaded',
            'Your screenshot and payment details were received. The order is waiting for manual review.'
        );

        $this->notifyOrder(
            $order,
            'submitted',
            'Order received',
            'We have your screenshot on file and the review queue has started for this order.'
        );
    }

    public function orderApproved(Order $order): void
    {
        $expiresAt = $order->token_expires_at?->format('M d, Y H:i');

        $this->addOrderUpdate(
            $order,
            'approved',
            'Order approved and delivered',
            $expiresAt
                ? "Your download link is active until {$expiresAt}."
                : 'Your private download link is now active.'
        );

        $this->notifyOrder(
            $order,
            'approved',
            'Download ready',
            $expiresAt
                ? "Your order has been approved. The download link is active until {$expiresAt}."
                : 'Your order has been approved and the private download link is ready.'
        );
    }

    public function orderRejected(Order $order): void
    {
        $body = $order->notes ?: 'The payment proof needs another look. Please review the order notes and submit a new request if needed.';

        $this->addOrderUpdate(
            $order,
            'rejected',
            'Payment proof needs attention',
            $body
        );

        $this->notifyOrder(
            $order,
            'rejected',
            'Order needs attention',
            $body
        );
    }

    public function downloadRefreshed(Order $order): void
    {
        $expiresAt = $order->token_expires_at?->format('M d, Y H:i');

        $this->addOrderUpdate(
            $order,
            'download_refresh',
            'Download link refreshed',
            $expiresAt
                ? "A fresh private link was issued. It expires on {$expiresAt}."
                : 'A fresh private link was issued for your order.'
        );

        $this->notifyOrder(
            $order,
            'download_refresh',
            'Fresh download link issued',
            $expiresAt
                ? "A new download link has been generated. It expires on {$expiresAt}."
                : 'A new download link has been generated for your order.'
        );
    }

    public function supportRequestLogged(BuyerSupportRequest $supportRequest): void
    {
        $order = $supportRequest->order;
        $title = match ($supportRequest->type) {
            BuyerSupportRequest::TYPE_REVIEW => 'Review request sent',
            BuyerSupportRequest::TYPE_DOWNLOAD => 'Download refresh request sent',
            default => 'Support request sent',
        };

        if ($order) {
            $this->addOrderUpdate(
                $order,
                'support_request',
                $title,
                $supportRequest->subject
            );
        }

        $this->notifyUser(
            $supportRequest->user,
            $order,
            'support_request',
            $title,
            'Your request has been logged in the buyer dashboard and will be reviewed manually.'
        );
    }

    public function profileUpdated(User $user): void
    {
        $this->notifyUser(
            $user,
            null,
            'profile_update',
            'Profile updated',
            'Your buyer profile details were updated successfully.'
        );
    }

    public function notifyUser(User $user, ?Order $order, string $type, string $title, ?string $body = null): BuyerNotification
    {
        return BuyerNotification::create([
            'user_id' => $user->id,
            'order_id' => $order?->id,
            'type' => $type,
            'title' => $title,
            'body' => $body,
            'action_url' => $order ? route('account.orders.show', $order) : route('account.index'),
        ]);
    }

    protected function notifyOrder(Order $order, string $type, string $title, ?string $body = null): ?BuyerNotification
    {
        $buyer = $this->resolveBuyer($order);

        if (! $buyer) {
            return null;
        }

        return $this->notifyUser($buyer, $order, $type, $title, $body);
    }

    protected function addOrderUpdate(Order $order, string $type, string $title, ?string $body = null): void
    {
        $order->orderUpdates()->create([
            'type' => $type,
            'title' => $title,
            'body' => $body,
        ]);
    }

    protected function resolveBuyer(Order $order): ?User
    {
        if ($order->relationLoaded('user') && $order->user) {
            return $order->user;
        }

        if ($order->user_id) {
            return $order->user()->first();
        }

        return User::query()->where('email', $order->buyer_email)->first();
    }
}
