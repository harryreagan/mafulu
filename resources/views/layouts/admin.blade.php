<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ trim($__env->yieldContent('title', 'Mafulu Admin')) }}</title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=DM+Sans:wght@300;400;500&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
        <style>
            :root {
                --ink: #0f0e0d;
                --ink-muted: #6b6763;
                --ink-faint: #b8b4af;
                --paper: #faf9f7;
                --paper-warm: #f2f0ec;
                --paper-card: #ffffff;
                --accent: #c8502a;
                --border: rgba(15,14,13,0.1);
                --border-strong: rgba(15,14,13,0.18);
                --purple: #4a3fa8;
                --purple-bg: #eeedf8;
                --teal: #1a7a5e;
                --teal-bg: #e3f4ef;
                --amber: #a05f10;
                --amber-bg: #fdf0dc;
                --blue: #1e5fa8;
                --blue-bg: #e3edf8;
            }
            * { box-sizing: border-box; }
            body { margin: 0; background: var(--paper); color: var(--ink); font-family: 'DM Sans', sans-serif; }
            a { color: inherit; text-decoration: none; }
            h1, h2, h3 { margin: 0; color: var(--ink); font-family: 'DM Serif Display', serif; font-weight: 400; letter-spacing: -0.03em; }
            p { margin: 0; color: var(--ink-muted); font-weight: 300; line-height: 1.7; }
            button, input, select, textarea { font: inherit; }
            input, select, textarea { width: 100%; border: 1px solid var(--border-strong); border-radius: 6px; background: var(--paper-card); color: var(--ink); padding: 10px 14px; font-size: 14px; outline: none; }
            input:focus, select:focus, textarea:focus { border-color: var(--ink); }
            label { display: block; margin-bottom: 6px; color: var(--ink-muted); font-size: 13px; font-weight: 400; }
            code, .mono { font-family: 'JetBrains Mono', monospace; }
            .admin-shell { min-height: 100vh; display: grid; grid-template-columns: 240px minmax(0, 1fr); }
            .admin-sidebar { background: var(--paper-card); border-right: 1px solid var(--border); padding: 24px 18px; display: flex; flex-direction: column; gap: 20px; }
            .admin-brand { font-family: 'DM Serif Display', serif; font-size: 20px; letter-spacing: -0.03em; }
            .admin-brand span { color: var(--accent); font-style: italic; }
            .admin-nav { display: grid; gap: 8px; }
            .admin-nav a { padding: 10px 16px; border-radius: 6px; color: var(--ink-muted); font-size: 14px; font-weight: 400; transition: background 0.2s ease, color 0.2s ease; }
            .admin-nav a:hover, .admin-nav a.is-active { background: var(--paper-warm); color: var(--ink); font-weight: 500; }
            .admin-main { padding: 2.5rem; }
            .notice-card { border: 1px solid var(--border); background: var(--paper-card); border-radius: 8px; padding: 14px 16px; color: var(--ink-muted); font-size: 14px; margin-bottom: 1rem; }
            .notice-card.error { border-color: #f09595; color: #a32d2d; background: #fcebeb; }
            .surface-card { background: var(--paper-card); border: 1px solid var(--border); border-radius: 12px; padding: 1.75rem; }
            .form-grid { display: grid; gap: 1.25rem; }
            .button-primary, .button-ghost, .button-danger { display: inline-flex; align-items: center; justify-content: center; gap: 8px; cursor: pointer; transition: background 0.2s ease, color 0.2s ease, border-color 0.2s ease; }
            .button-primary { border: none; border-radius: 4px; padding: 9px 18px; background: var(--ink); color: var(--paper); font-family: 'JetBrains Mono', monospace; font-size: 12px; font-weight: 500; letter-spacing: 0.06em; text-transform: uppercase; }
            .button-primary:hover { background: var(--accent); }
            .button-ghost { border: 1px solid var(--border-strong); border-radius: 4px; background: transparent; color: var(--ink-muted); padding: 9px 16px; font-size: 13px; }
            .button-ghost:hover { border-color: var(--ink); color: var(--ink); }
            .button-danger { border: 1px solid #f09595; border-radius: 4px; background: transparent; color: #a32d2d; padding: 9px 16px; font-size: 13px; }
            .button-danger:hover { background: #fcebeb; }
            .section-head { display: flex; align-items: flex-end; justify-content: space-between; gap: 1.5rem; padding-bottom: 1rem; border-bottom: 1px solid var(--border); margin-bottom: 2rem; }
            .section-title { font-size: 2rem; letter-spacing: -0.02em; }
            .eyebrow { margin-bottom: 10px; color: var(--ink-faint); font-size: 11px; font-weight: 500; letter-spacing: 0.14em; text-transform: uppercase; }
            .stats-grid { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 1rem; }
            .layout-dashboard { display: grid; grid-template-columns: minmax(0, 0.95fr) minmax(0, 1.05fr); gap: 1.5rem; }
            .layout-admin-detail { display: grid; grid-template-columns: minmax(320px, 0.8fr) minmax(0, 1.2fr); gap: 1.5rem; }
            .data-table-wrap { overflow: hidden; border: 1px solid var(--border); border-radius: 12px; background: var(--paper-card); }
            .data-table-scroll { overflow-x: auto; }
            .data-table { width: 100%; border-collapse: collapse; }
            .data-table th { border-bottom: 1px solid var(--border); padding: 10px 16px; text-align: left; color: var(--ink-faint); font-size: 11px; font-weight: 500; letter-spacing: 0.08em; text-transform: uppercase; }
            .data-table td { border-bottom: 1px solid var(--border); padding: 14px 16px; color: var(--ink); font-size: 14px; vertical-align: top; }
            .data-table tr:hover td { background: var(--paper-warm); }
            .status-badge { display: inline-flex; align-items: center; justify-content: center; border-radius: 4px; padding: 4px 10px; font-family: 'JetBrains Mono', monospace; font-size: 10px; font-weight: 500; letter-spacing: 0.08em; text-transform: uppercase; }
            .status-pending { background: #fdf0dc; color: #a05f10; }
            .status-screenshot_uploaded { background: #e3edf8; color: #1e5fa8; }
            .status-confirmed { background: #e3f4ef; color: #1a7a5e; }
            .status-delivered { background: #eeedf8; color: #4a3fa8; }
            .status-rejected { background: #fcebeb; color: #a32d2d; }
            .badge { display: inline-flex; align-items: center; justify-content: center; border-radius: 4px; padding: 4px 10px; font-family: 'JetBrains Mono', monospace; font-size: 11px; font-weight: 500; letter-spacing: 0.08em; text-transform: uppercase; }
            .badge-ebook { background: var(--purple-bg); color: var(--purple); }
            .badge-template { background: var(--teal-bg); color: var(--teal); }
            .badge-software { background: var(--blue-bg); color: var(--blue); }
            .badge-course { background: var(--amber-bg); color: var(--amber); }
            .detail-list { display: grid; }
            .detail-row { display: flex; align-items: flex-start; justify-content: space-between; gap: 1.25rem; padding: 12px 0; border-bottom: 1px solid var(--border); }
            .detail-row:last-child { border-bottom: none; }
            .detail-label { color: var(--ink-muted); font-size: 13px; }
            .detail-value { color: var(--ink); font-size: 14px; text-align: right; }
            .detail-value.mono { font-size: 12px; font-family: 'JetBrains Mono', monospace; }
            .upload-field { border: 1px dashed var(--border-strong); border-radius: 8px; padding: 2rem; text-align: center; background: var(--paper-warm); }
            .upload-field label { margin-bottom: 0.5rem; color: var(--ink-muted); font-family: 'JetBrains Mono', monospace; font-size: 12px; letter-spacing: 0.06em; text-transform: uppercase; }
            @media (max-width: 900px) {
                .admin-shell { grid-template-columns: 1fr; }
                .admin-sidebar { border-right: none; border-bottom: 1px solid var(--border); }
                .admin-main { padding: 1.5rem; }
                .section-head, .detail-row { flex-direction: column; align-items: flex-start; }
                .stats-grid, .layout-dashboard, .layout-admin-detail { grid-template-columns: 1fr; }
            }
        </style>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body>
        <div class="admin-shell">
            <aside class="admin-sidebar">
                <a href="{{ route('admin.dashboard') }}" class="admin-brand">Mafu<span>lu</span> Admin</a>
                <nav class="admin-nav">
                    <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'is-active' : '' }}">Dashboard</a>
                    <a href="{{ route('admin.orders.index') }}" class="{{ request()->routeIs('admin.orders.*') ? 'is-active' : '' }}">Orders</a>
                    <a href="{{ route('admin.products.index') }}" class="{{ request()->routeIs('admin.products.*') ? 'is-active' : '' }}">Products</a>
                    <a href="{{ route('admin.coupons.index') }}" class="{{ request()->routeIs('admin.coupons.*') ? 'is-active' : '' }}">Coupons</a>
                </nav>
                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button type="submit" class="button-ghost" style="width: 100%;">Log out</button>
                </form>
            </aside>

            <main class="admin-main">
                @if (session('status'))
                    <div class="notice-card">{{ session('status') }}</div>
                @endif

                @if ($errors->any())
                    <div class="notice-card error">{{ $errors->first() }}</div>
                @endif

                @yield('content')
            </main>
        </div>
    </body>
</html>


