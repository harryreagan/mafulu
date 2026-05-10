<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ trim($__env->yieldContent('title', 'Mafulu')) }}</title>
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
                --accent-light: #f5e8e3;
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
            html { scroll-behavior: smooth; }
            body { margin: 0; background: var(--paper); color: var(--ink); font-family: 'DM Sans', sans-serif; font-weight: 400; overflow-x: hidden; }
            a { color: inherit; text-decoration: none; }
            img, svg { display: block; max-width: 100%; }
            h1, h2, h3 { margin: 0; color: var(--ink); font-family: 'DM Serif Display', serif; font-weight: 400; letter-spacing: -0.03em; }
            p { margin: 0; color: var(--ink-muted); font-weight: 300; line-height: 1.7; }
            button, input, select, textarea { font: inherit; }
            input, select, textarea { width: 100%; border: 1px solid var(--border-strong); border-radius: 6px; background: var(--paper-card); color: var(--ink); padding: 10px 14px; font-size: 14px; outline: none; transition: border-color 0.2s ease; }
            input:focus, select:focus, textarea:focus { border-color: var(--ink); }
            label { display: block; margin-bottom: 6px; color: var(--ink-muted); font-size: 13px; font-weight: 400; }
            code, .mono { font-family: 'JetBrains Mono', monospace; }
            .site-shell { min-height: 100vh; padding-top: 60px; overflow-x: clip; }
            .topbar { position: fixed; inset: 0 0 auto 0; z-index: 50; height: 60px; background: rgba(250, 249, 247, 0.92); backdrop-filter: blur(12px); border-bottom: 1px solid var(--border); }
            .topbar-inner { width: 100%; max-width: 1280px; height: 100%; margin: 0 auto; padding: 0 24px; display: flex; align-items: center; justify-content: space-between; gap: 24px; }
            .brand-mark { font-family: 'DM Serif Display', serif; font-size: 22px; color: var(--ink); letter-spacing: -0.03em; white-space: nowrap; }
            .brand-mark span { color: var(--accent); font-style: italic; }
            .topbar-nav { display: flex; align-items: center; gap: 12px; flex-wrap: wrap; justify-content: flex-end; }
            .topbar-nav form { margin: 0; }
            .nav-link { color: var(--ink-muted); font-size: 13.5px; font-weight: 400; transition: color 0.2s ease; }
            .nav-link:hover, .nav-link.is-active { color: var(--ink); }
            .main-frame { width: 100%; max-width: 1280px; margin: 0 auto; padding: 5rem 4rem 4rem; }
            .footer-shell { border-top: 1px solid var(--border); margin-top: 2rem; }
            .footer-inner { width: 100%; max-width: 1280px; margin: 0 auto; padding: 1.5rem 4rem 2rem; display: flex; justify-content: space-between; gap: 1rem; color: var(--ink-muted); font-size: 13px; }
            .flash-stack { display: grid; gap: 12px; margin-bottom: 1.5rem; }
            .notice-card { border: 1px solid var(--border); background: var(--paper-card); border-radius: 8px; padding: 14px 16px; color: var(--ink-muted); font-size: 14px; }
            .notice-card.error { border-color: #f09595; color: #a32d2d; background: #fcebeb; }
            .section-shell { display: grid; gap: 2rem; }
            .section-shell > *, .section-head > *, .catalog-grid > *, .two-up > *, .metrics-grid > *, .layout-split-wide > *, .layout-split-product > * { min-width: 0; }
            .section-head { display: flex; align-items: flex-end; justify-content: space-between; gap: 1.5rem; padding-bottom: 1rem; border-bottom: 1px solid var(--border); margin-bottom: 2.5rem; }
            .section-title { font-size: 2rem; letter-spacing: -0.02em; }
            .eyebrow { margin-bottom: 10px; color: var(--ink-faint); font-size: 11px; font-weight: 500; letter-spacing: 0.14em; text-transform: uppercase; }
            .hero-copy { max-width: 720px; display: grid; gap: 1rem; }
            .hero-title { font-size: clamp(3rem, 7vw, 5.25rem); line-height: 0.94; }
            .hero-text { max-width: 640px; font-size: 16px; }
            .ticker { overflow: hidden; width: 100%; border-bottom: 1px solid rgba(255,255,255,0.08); background: var(--accent); margin: -5rem 0 4rem; padding: 10px 0; }
            .ticker-track { width: max-content; display: flex; gap: 24px; white-space: nowrap; color: rgba(255,255,255,0.9); font-family: 'JetBrains Mono', monospace; font-size: 11px; letter-spacing: 0.1em; text-transform: uppercase; animation: tickerMove 30s linear infinite; }
            .ticker-track span { display: inline-flex; align-items: center; gap: 24px; }
            .ticker-track span::after { content: '?'; }
            .surface-card { background: var(--paper-card); border: 1px solid var(--border); border-radius: 12px; padding: 1.75rem; }
            .surface-card.soft { background: var(--paper-warm); }
            .surface-card.interactive { transition: border-color 0.2s ease, transform 0.2s ease; }
            .surface-card.interactive:hover { border-color: var(--border-strong); transform: translateY(-2px); }
            .button-primary, .button-ghost, .button-danger, .filter-pill { display: inline-flex; align-items: center; justify-content: center; gap: 8px; cursor: pointer; transition: background 0.2s ease, color 0.2s ease, border-color 0.2s ease, transform 0.2s ease; }
            .button-primary { border: none; border-radius: 4px; padding: 9px 18px; background: var(--ink); color: var(--paper); font-family: 'JetBrains Mono', monospace; font-size: 12px; font-weight: 500; letter-spacing: 0.06em; text-transform: uppercase; }
            .button-primary:hover { background: var(--accent); }
            .button-ghost { border: 1px solid var(--border-strong); border-radius: 4px; background: transparent; color: var(--ink-muted); padding: 9px 16px; font-size: 13px; font-weight: 400; }
            .button-ghost:hover { border-color: var(--ink); color: var(--ink); }
            .button-danger { border: 1px solid #f09595; border-radius: 4px; background: transparent; color: #a32d2d; padding: 9px 16px; font-size: 13px; font-weight: 400; }
            .button-danger:hover { background: #fcebeb; }
            .filter-row { display: flex; flex-wrap: wrap; gap: 10px; }
            .filter-pill { border: 1px solid var(--border-strong); border-radius: 999px; background: transparent; color: var(--ink-muted); padding: 6px 14px; font-family: 'JetBrains Mono', monospace; font-size: 11px; font-weight: 500; letter-spacing: 0.06em; text-transform: lowercase; }
            .filter-pill.is-active, .filter-pill:hover { border-color: var(--ink); background: var(--ink); color: var(--paper); }
            .catalog-grid { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 1.4rem; }
            .product-card { display: grid; grid-template-rows: auto auto 1fr auto; gap: 1rem; height: 100%; background: var(--paper-card); border: 1px solid var(--border); border-radius: 12px; padding: 1.35rem; transition: border-color 0.2s ease, transform 0.2s ease; }
            .product-card:hover { border-color: var(--border-strong); transform: translateY(-3px); }
            .product-card-top { display: flex; align-items: flex-start; justify-content: space-between; gap: 12px; }
            .product-card-tags { display: flex; flex-wrap: wrap; gap: 8px; justify-content: flex-end; min-width: 0; }
            .product-card-copy { display: grid; gap: 0.7rem; min-width: 0; }
            .category-icon { width: 42px; height: 42px; border-radius: 10px; display: inline-flex; align-items: center; justify-content: center; font-family: 'JetBrains Mono', monospace; font-size: 13px; font-weight: 500; letter-spacing: 0.08em; text-transform: uppercase; flex-shrink: 0; }
            .product-card-title, .product-card-title a { color: var(--ink); font-size: 15px; font-weight: 500; line-height: 1.35; text-wrap: balance; }
            .product-card-text { color: var(--ink-muted); font-size: 13px; font-weight: 300; line-height: 1.62; display: -webkit-box; -webkit-line-clamp: 4; -webkit-box-orient: vertical; overflow: hidden; }
            .product-card-foot { margin-top: auto; padding-top: 0.95rem; border-top: 1px solid var(--border); display: flex; align-items: flex-end; justify-content: space-between; gap: 0.9rem; }
            .price-display { color: var(--ink); font-family: 'DM Serif Display', serif; font-size: 20px; line-height: 1; }
            .price-note { margin-top: 6px; color: var(--ink-faint); font-family: 'JetBrains Mono', monospace; font-size: 11px; font-weight: 400; line-height: 1.55; }
            .form-grid { display: grid; gap: 1.25rem; }
            .two-up { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.25rem; }
            .layout-split-wide { display: grid; grid-template-columns: minmax(0, 1.2fr) minmax(320px, 0.95fr); align-items: start; gap: 3rem; }
            .layout-split-product { display: grid; grid-template-columns: minmax(0, 1.2fr) minmax(320px, 0.8fr); align-items: start; gap: 1.25rem; }
            .sidebar-sticky { position: sticky; top: 92px; }
            .upload-field { border: 1px dashed var(--border-strong); border-radius: 8px; padding: 2rem; text-align: center; background: var(--paper-warm); }
            .upload-field label { margin-bottom: 0.5rem; color: var(--ink-muted); font-family: 'JetBrains Mono', monospace; font-size: 12px; letter-spacing: 0.06em; text-transform: uppercase; }
            .wallet-box, .mono-box { border: 1px solid var(--border); border-radius: 6px; background: var(--paper-warm); padding: 10px 14px; color: var(--ink); font-family: 'JetBrains Mono', monospace; font-size: 12px; word-break: break-all; }
            .stats-grid { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 1rem; }
            .data-table-wrap { overflow: hidden; border: 1px solid var(--border); border-radius: 12px; background: var(--paper-card); }
            .data-table-scroll { overflow-x: auto; }
            .data-table { width: 100%; border-collapse: collapse; }
            .data-table th { border-bottom: 1px solid var(--border); padding: 10px 16px; text-align: left; color: var(--ink-faint); font-size: 11px; font-weight: 500; letter-spacing: 0.08em; text-transform: uppercase; }
            .data-table td { border-bottom: 1px solid var(--border); padding: 14px 16px; color: var(--ink); font-size: 14px; vertical-align: top; }
            .data-table tr:hover td { background: var(--paper-warm); }
            .detail-list { display: grid; }
            .detail-row { display: flex; align-items: flex-start; justify-content: space-between; gap: 1.25rem; padding: 12px 0; border-bottom: 1px solid var(--border); }
            .detail-row:last-child { border-bottom: none; }
            .detail-label { color: var(--ink-muted); font-size: 13px; font-weight: 400; }
            .detail-value { color: var(--ink); font-size: 14px; text-align: right; }
            .detail-value.mono { font-size: 12px; }
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
            .icon-ebook { background: var(--purple-bg); color: var(--purple); }
            .icon-template { background: var(--teal-bg); color: var(--teal); }
            .icon-software { background: var(--blue-bg); color: var(--blue); }
            .icon-course { background: var(--amber-bg); color: var(--amber); }
            .metrics-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1rem; }
            .stack-list { display: grid; gap: 1rem; }
            .store-meta { display: flex; flex-wrap: wrap; align-items: flex-end; justify-content: space-between; gap: 1rem; }
            .store-intro { display: grid; gap: 0.7rem; max-width: 760px; }
            .store-stat { display: grid; gap: 0.35rem; min-width: 170px; padding: 1rem 1.1rem; border: 1px solid var(--border); border-radius: 12px; background: var(--paper-card); }
            .toolbar-card { display: grid; gap: 1rem; border: 1px solid var(--border); border-radius: 14px; background: var(--paper-card); padding: 1.35rem; }
            .toolbar-head { display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 0.75rem; padding-bottom: 0.9rem; border-bottom: 1px solid var(--border); }
            .toolbar-grid { display: grid; grid-template-columns: minmax(0, 1.5fr) repeat(2, minmax(180px, 0.72fr)) auto; gap: 1rem; align-items: end; }
            .toolbar-grid > * { min-width: 0; }
            .toolbar-search { min-width: 0; }
            .toolbar-action { justify-self: end; }
            .toolbar-note { color: var(--ink-faint); font-family: 'JetBrains Mono', monospace; font-size: 11px; letter-spacing: 0.08em; text-transform: uppercase; }
            .fade-up { animation: fadeUp 0.6s ease forwards; }
            .fade-up-1 { animation-delay: 0.1s; opacity: 0; }
            .fade-up-2 { animation-delay: 0.22s; opacity: 0; }
            .fade-up-3 { animation-delay: 0.34s; opacity: 0; }
            .pagination-shell { margin-top: 1.5rem; }
            @keyframes fadeUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
            @keyframes tickerMove { from { transform: translateX(0); } to { transform: translateX(-50%); } }
            @media (min-width: 1500px) {
                .catalog-grid { grid-template-columns: repeat(4, minmax(0, 1fr)); }
            }
            @media (max-width: 1200px) {
                .toolbar-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
                .toolbar-search { grid-column: 1 / -1; }
                .toolbar-action { justify-self: start; }
                .main-frame { padding: 4rem 2rem 3rem; }
                .footer-inner { padding: 1.5rem 2rem 2rem; }
                .section-head { flex-direction: column; align-items: flex-start; }
            }
            @media (max-width: 900px) {
                .topbar { height: auto; }
                .topbar-inner { min-height: 60px; padding: 12px 18px; flex-wrap: wrap; align-items: flex-start; }
                .site-shell { padding-top: 124px; }
                .main-frame { padding: 3rem 1.5rem; }
                .footer-inner { padding: 1.5rem; flex-direction: column; }
                .ticker { margin: -3rem 0 2.5rem; }
                .section-head, .product-card-foot, .detail-row { flex-direction: column; align-items: flex-start; }
                .topbar-nav { width: 100%; justify-content: space-between; }
                .catalog-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
                .two-up, .metrics-grid, .stats-grid, .layout-split-wide, .layout-split-product { grid-template-columns: 1fr; }
            }
            @media (max-width: 640px) {
                .toolbar-grid { grid-template-columns: 1fr; }
                .toolbar-action { width: 100%; }
                .catalog-grid { grid-template-columns: 1fr; }
                .sidebar-sticky { position: static; }
            }
        </style>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body>
        <div class="site-shell">
            <header class="topbar">
                <div class="topbar-inner">
                    <a href="{{ route('home') }}" class="brand-mark">Mafu<span>lu</span></a>
                    <nav class="topbar-nav">
                        <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'is-active' : '' }}">Home</a>
                        @auth
                            <a href="{{ route('account.index') }}" class="nav-link {{ request()->routeIs('account.*') ? 'is-active' : '' }}">Account</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="button-ghost">Logout</button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="nav-link {{ request()->routeIs('login') ? 'is-active' : '' }}">Login</a>
                            <a href="{{ route('register') }}" class="nav-link {{ request()->routeIs('register') ? 'is-active' : '' }}">Register</a>
                        @endauth
                        <a href="{{ route('store.index') }}" class="button-primary">Browse</a>
                    </nav>
                </div>
            </header>

            <main class="main-frame">
                @if (session('status'))
                    <div class="flash-stack">
                        <div class="notice-card">{{ session('status') }}</div>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="flash-stack">
                        <div class="notice-card error">{{ $errors->first() }}</div>
                    </div>
                @endif

                @yield('content')
            </main>

            <footer class="footer-shell">
                <div class="footer-inner">
                    <p>Single-seller digital products with private delivery and manual review.</p>
                    <p>&copy; {{ now()->year }} Mafulu</p>
                </div>
            </footer>
        </div>
    </body>
</html>










