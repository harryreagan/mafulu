@extends('layouts.app')

@section('title', 'Order #' . $order->id . ' | Mafulu')

@section('content')
    @php
        $statusClasses = [
            'pending' => 'status-badge status-pending',
            'screenshot_uploaded' => 'status-badge status-screenshot_uploaded',
            'confirmed' => 'status-badge status-confirmed',
            'delivered' => 'status-badge status-delivered',
            'rejected' => 'status-badge status-rejected',
        ];
    @endphp

    <div class="section-shell">
        <section>
            <div class="section-head">
                <div>
                    <p class="eyebrow">Order detail</p>
                    <h1 class="section-title">Order #{{ $order->id }}</h1>
                    <p style="margin-top: 0.75rem; max-width: 760px;">Follow the full status history, open your receipt, and request help from the buyer dashboard.</p>
                </div>
                <div style="display: flex; flex-wrap: wrap; gap: 0.75rem;">
                    <a href="{{ route('account.index') }}" class="button-ghost">Back to dashboard</a>
                    <a href="{{ $receiptUrl }}" class="button-ghost">Open receipt</a>
                    @if ($order->downloadIsActive())
                        <a href="{{ route('download', $order->download_token) }}" class="button-primary">Download file</a>
                    @endif
                </div>
            </div>
        </section>

        <section class="layout-split-wide" style="gap: 1.5rem; align-items: start;">
            <div class="section-shell" style="gap: 1.5rem;">
                <article class="surface-card">
                    <div style="display: flex; align-items: flex-start; justify-content: space-between; gap: 1rem; flex-wrap: wrap; margin-bottom: 1.25rem;">
                        <div>
                            <p class="eyebrow">Summary</p>
                            <h2 style="font-size: 1.8rem;">{{ $order->product?->title ?? 'Unavailable product' }}</h2>
                        </div>
                        <span class="{{ $statusClasses[$order->status] ?? 'status-badge' }}">{{ str($order->status)->replace('_', ' ')->title() }}</span>
                    </div>

                    <div class="detail-list">
                        <div class="detail-row">
                            <div class="detail-label">Buyer</div>
                            <div class="detail-value">{{ $order->buyer_name }}</div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Email</div>
                            <div class="detail-value">{{ $order->buyer_email }}</div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Amount paid</div>
                            <div class="detail-value mono">${{ number_format((float) $order->amount_usd, 2) }}</div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Crypto payment</div>
                            <div class="detail-value mono">{{ strtoupper($order->crypto_currency) }} {{ number_format((float) $order->crypto_amount, $order->crypto_currency === 'BTC' ? 8 : 2) }}</div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Discount used</div>
                            <div class="detail-value mono">${{ number_format((float) $order->discount_usd, 2) }}</div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Created</div>
                            <div class="detail-value">{{ $order->created_at->format('M d, Y H:i') }}</div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Download expires</div>
                            <div class="detail-value">{{ $order->token_expires_at?->format('M d, Y H:i') ?? 'Not issued yet' }}</div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Notes</div>
                            <div class="detail-value">{{ $order->notes ?: 'No extra notes yet.' }}</div>
                        </div>
                    </div>
                </article>

                <article class="surface-card">
                    <div style="margin-bottom: 1.25rem;">
                        <p class="eyebrow">Timeline</p>
                        <h2 style="font-size: 1.8rem;">Order activity</h2>
                    </div>

                    <div class="stack-list">
                        @forelse ($order->orderUpdates as $update)
                            <div class="surface-card soft" style="padding: 1rem; display: grid; gap: 0.4rem;">
                                <div style="display: flex; align-items: center; justify-content: space-between; gap: 1rem; flex-wrap: wrap;">
                                    <div style="color: var(--ink); font-weight: 500;">{{ $update->title }}</div>
                                    <div class="mono" style="font-size: 11px; color: var(--ink-faint);">{{ $update->created_at->diffForHumans() }}</div>
                                </div>
                                @if ($update->body)
                                    <p>{{ $update->body }}</p>
                                @endif
                            </div>
                        @empty
                            <p>No timeline entries yet. New account-linked orders will populate this automatically.</p>
                        @endforelse
                    </div>
                </article>

                <article class="surface-card">
                    <div style="margin-bottom: 1.25rem;">
                        <p class="eyebrow">Downloads</p>
                        <h2 style="font-size: 1.8rem;">Delivery log</h2>
                    </div>

                    @if ($order->downloadAttempts->isEmpty())
                        <p>No download attempts recorded yet.</p>
                    @else
                        <div class="data-table-wrap data-table-scroll">
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th>Attempted</th>
                                        <th>IP</th>
                                        <th>Status</th>
                                        <th>Reason</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($order->downloadAttempts as $attempt)
                                        <tr>
                                            <td>{{ $attempt->attempted_at->format('M d, Y H:i') }}</td>
                                            <td class="mono">{{ $attempt->ip_address }}</td>
                                            <td>{{ $attempt->was_successful ? 'Successful' : 'Blocked' }}</td>
                                            <td>{{ $attempt->reason ?: 'N/A' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </article>
            </div>

            <div class="section-shell" style="gap: 1.5rem;">
                <article class="surface-card">
                    <div style="margin-bottom: 1.25rem;">
                        <p class="eyebrow">Quick actions</p>
                        <h2 style="font-size: 1.8rem;">Need help?</h2>
                    </div>

                    <div class="stack-list">
                        @if (in_array($order->status, ['pending', 'screenshot_uploaded', 'rejected'], true))
                            <form method="POST" action="{{ route('account.orders.request-review', $order) }}" class="form-grid">
                                @csrf
                                <div>
                                    <label for="review_message">Review note</label>
                                    <textarea id="review_message" name="message" rows="4" placeholder="Add any extra context for the manual review team."></textarea>
                                </div>
                                <button type="submit" class="button-primary">Request review</button>
                            </form>
                        @endif

                        <form method="POST" action="{{ route('account.orders.request-download', $order) }}" class="form-grid">
                            @csrf
                            <div>
                                <label for="download_message">Download request</label>
                                <textarea id="download_message" name="message" rows="4" placeholder="Use this if your link expired or you need a fresh delivery email."></textarea>
                            </div>
                            <button type="submit" class="button-ghost">Request fresh download link</button>
                        </form>
                    </div>
                </article>

                <article class="surface-card">
                    <div style="margin-bottom: 1.25rem;">
                        <p class="eyebrow">Support history</p>
                        <h2 style="font-size: 1.8rem;">Requests tied to this order</h2>
                    </div>

                    <div class="stack-list">
                        @forelse ($order->supportRequests as $supportRequest)
                            <div class="surface-card soft" style="padding: 1rem; display: grid; gap: 0.45rem;">
                                <div style="display: flex; align-items: center; justify-content: space-between; gap: 1rem; flex-wrap: wrap;">
                                    <div style="color: var(--ink); font-weight: 500;">{{ $supportRequest->subject }}</div>
                                    <span class="status-badge {{ $supportRequest->status === 'closed' ? 'status-delivered' : 'status-screenshot_uploaded' }}">{{ ucfirst($supportRequest->status) }}</span>
                                </div>
                                <p>{{ $supportRequest->message }}</p>
                                <div class="mono" style="font-size: 11px; color: var(--ink-faint); text-transform: uppercase; letter-spacing: 0.08em;">{{ $supportRequest->type }} / {{ $supportRequest->created_at->format('M d, Y') }}</div>
                            </div>
                        @empty
                            <p>No support requests have been logged for this order.</p>
                        @endforelse
                    </div>
                </article>
            </div>
        </section>

        @if ($relatedProducts->isNotEmpty())
            <section>
                <div class="section-head" style="margin-bottom: 1.5rem;">
                    <div>
                        <p class="eyebrow">Related products</p>
                        <h2 class="section-title">Keep browsing while this order moves.</h2>
                    </div>
                </div>

                <div class="catalog-grid">
                    @foreach ($relatedProducts as $product)
                        <x-product-card :product="$product" />
                    @endforeach
                </div>
            </section>
        @endif
    </div>
@endsection
