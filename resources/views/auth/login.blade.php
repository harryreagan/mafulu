@extends('layouts.app')

@section('title', 'Login | Mafulu')

@section('content')
    <div class="surface-card" style="max-width: 560px; margin: 0 auto; display: grid; gap: 1.5rem;">
        <div>
            <p class="eyebrow">Shared access</p>
            <h1 class="section-title">Sign in to continue.</h1>
            <p style="margin-top: 0.75rem;">Use the same login form for both buyer accounts and admin access. Buyers now need an account before checkout so orders, downloads, and support updates stay in one place.</p>
        </div>

        <form method="POST" action="{{ route('login.store') }}" class="form-grid">
            @csrf

            <div>
                <label for="email">Email address</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus>
            </div>

            <div>
                <label for="password">Password</label>
                <input id="password" type="password" name="password" required>
            </div>

            <label style="display: flex; align-items: center; gap: 10px; margin: 0;">
                <input type="checkbox" name="remember" value="1" style="width: 16px; height: 16px; margin: 0;">
                <span style="font-size: 14px; color: var(--ink-muted);">Keep me signed in</span>
            </label>

            <button type="submit" class="button-primary" style="width: 100%;">Login</button>
        </form>

        <p style="font-size: 14px;">New here? <a href="{{ route('register') }}" style="color: var(--ink); font-weight: 500;">Create your account</a></p>
    </div>
@endsection
