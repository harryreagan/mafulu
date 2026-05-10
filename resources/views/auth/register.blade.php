@extends('layouts.app')

@section('title', 'Register | Mafulu')

@section('content')
    <div class="surface-card" style="max-width: 560px; margin: 0 auto; display: grid; gap: 1.5rem;">
        <div>
            <p class="eyebrow">Buyer access</p>
            <h1 class="section-title">Create your account.</h1>
            <p style="margin-top: 0.75rem;">Register once, then use your dashboard to follow order status, open receipts, manage downloads, and send support requests.</p>
        </div>

        <form method="POST" action="{{ route('register.store') }}" class="form-grid">
            @csrf

            <div>
                <label for="name">Full name</label>
                <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus>
            </div>

            <div>
                <label for="email">Email address</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required>
            </div>

            <div>
                <label for="password">Password</label>
                <input id="password" type="password" name="password" required>
            </div>

            <div>
                <label for="password_confirmation">Confirm password</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required>
            </div>

            <button type="submit" class="button-primary" style="width: 100%;">Register</button>
        </form>

        <p style="font-size: 14px;">Already have an account? <a href="{{ route('login') }}" style="color: var(--ink); font-weight: 500;">Sign in</a></p>
    </div>
@endsection
