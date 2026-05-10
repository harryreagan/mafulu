@extends('layouts.app')

@section('title', 'Admin Login | Mafulu')

@section('content')
    <div class="surface-card" style="max-width: 480px; margin: 0 auto; display: grid; gap: 1.5rem;">
        <div>
            <p class="eyebrow">Admin access</p>
            <h1 class="section-title">Sign in to Mafulu Admin.</h1>
            <p style="margin-top: 0.75rem;">Use the password stored in <code class="mono-box" style="display: inline-block; padding: 0.35rem 0.5rem;">ADMIN_PASSWORD</code>.</p>
        </div>

        <form method="POST" action="{{ route('admin.login.store') }}" class="form-grid">
            @csrf
            <div>
                <label for="password">Password</label>
                <input id="password" type="password" name="password">
            </div>

            <button type="submit" class="button-primary" style="width: 100%;">Log in</button>
        </form>
    </div>
@endsection
