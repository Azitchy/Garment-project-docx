<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Garment Management System' }}</title>
    <style>
        :root {
            --bg: #0f172a;
            --panel: rgba(15, 23, 42, 0.88);
            --panel-soft: rgba(30, 41, 59, 0.9);
            --text: #e2e8f0;
            --muted: #94a3b8;
            --accent: #f59e0b;
            --accent-2: #22c55e;
            --line: rgba(148, 163, 184, 0.2);
        }

        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            background:
                radial-gradient(circle at top left, rgba(245, 158, 11, 0.18), transparent 30%),
                radial-gradient(circle at top right, rgba(34, 197, 94, 0.14), transparent 28%),
                linear-gradient(180deg, #020617 0%, #0f172a 45%, #111827 100%);
            color: var(--text);
            min-height: 100vh;
        }

        a { color: inherit; text-decoration: none; }

        .shell {
            width: min(1180px, calc(100% - 32px));
            margin: 0 auto;
            padding: 24px 0 48px;
        }

        .nav {
            display: flex;
            justify-content: space-between;
            gap: 16px;
            align-items: center;
            padding: 18px 20px;
            border: 1px solid var(--line);
            border-radius: 24px;
            background: rgba(15, 23, 42, 0.7);
            backdrop-filter: blur(16px);
            position: sticky;
            top: 16px;
            z-index: 10;
        }

        .brand {
            display: flex;
            gap: 12px;
            align-items: center;
            font-weight: 800;
            letter-spacing: .02em;
        }

        .brand-mark {
            width: 42px;
            height: 42px;
            border-radius: 14px;
            display: grid;
            place-items: center;
            color: #111827;
            background: linear-gradient(135deg, #facc15, #fb923c);
            box-shadow: 0 16px 40px rgba(251, 146, 60, 0.28);
        }

        .nav-links {
            display: flex;
            gap: 14px;
            flex-wrap: wrap;
            color: var(--muted);
        }

        .nav-links a {
            padding: 10px 14px;
            border-radius: 999px;
            transition: 0.2s ease;
        }

        .nav-links a:hover {
            background: rgba(148, 163, 184, 0.14);
            color: white;
        }

        .hero, .card, .section {
            border: 1px solid var(--line);
            background: var(--panel);
            backdrop-filter: blur(16px);
            border-radius: 28px;
        }

        .hero {
            margin-top: 28px;
            padding: 42px;
            display: grid;
            grid-template-columns: 1.3fr .9fr;
            gap: 24px;
            overflow: hidden;
            position: relative;
        }

        .hero::after {
            content: "";
            position: absolute;
            inset: auto -80px -80px auto;
            width: 240px;
            height: 240px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(245, 158, 11, 0.24), transparent 70%);
            pointer-events: none;
        }

        .eyebrow {
            color: #fbbf24;
            text-transform: uppercase;
            letter-spacing: .18em;
            font-size: .75rem;
            font-weight: 700;
            margin-bottom: 14px;
        }

        h1, h2, h3, p { margin-top: 0; }

        h1 {
            font-size: clamp(2.4rem, 5vw, 4.8rem);
            line-height: .95;
            margin-bottom: 18px;
        }

        .lead {
            color: var(--muted);
            font-size: 1.05rem;
            line-height: 1.75;
            max-width: 64ch;
        }

        .actions {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin-top: 28px;
        }

        .button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 13px 18px;
            border-radius: 14px;
            font-weight: 700;
            border: 1px solid transparent;
        }

        .button.primary {
            background: linear-gradient(135deg, #fbbf24, #fb923c);
            color: #111827;
        }

        .button.secondary {
            border-color: rgba(148, 163, 184, 0.28);
            background: rgba(15, 23, 42, 0.4);
        }

        .stats {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 14px;
        }

        .stat {
            padding: 18px;
            border-radius: 20px;
            background: var(--panel-soft);
            border: 1px solid var(--line);
        }

        .stat strong {
            display: block;
            font-size: 1.8rem;
            margin-bottom: 6px;
        }

        .section {
            margin-top: 24px;
            padding: 28px;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 16px;
        }

        .item {
            padding: 22px;
            border-radius: 22px;
            background: rgba(15, 23, 42, 0.8);
            border: 1px solid var(--line);
        }

        .meta {
            color: var(--muted);
            font-size: .92rem;
        }

        .tag {
            display: inline-flex;
            align-items: center;
            padding: 6px 10px;
            border-radius: 999px;
            margin-bottom: 14px;
            background: rgba(245, 158, 11, 0.12);
            color: #fbbf24;
            font-size: .82rem;
            font-weight: 700;
        }

        .flash {
            margin: 20px 0 -4px;
            padding: 14px 18px;
            border-radius: 16px;
            border: 1px solid rgba(34, 197, 94, 0.3);
            background: rgba(34, 197, 94, 0.12);
            color: #bbf7d0;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            overflow: hidden;
        }

        .table th, .table td {
            padding: 14px 12px;
            text-align: left;
            border-bottom: 1px solid var(--line);
            vertical-align: top;
        }

        .table th {
            color: #cbd5e1;
            font-size: .9rem;
            letter-spacing: .04em;
            text-transform: uppercase;
        }

        .form {
            display: grid;
            gap: 16px;
        }

        .field {
            display: grid;
            gap: 8px;
        }

        .field label {
            font-weight: 700;
            color: #e2e8f0;
        }

        .field input, .field textarea, .field select {
            width: 100%;
            padding: 14px 16px;
            border-radius: 14px;
            border: 1px solid rgba(148, 163, 184, 0.22);
            background: rgba(2, 6, 23, 0.7);
            color: white;
            outline: none;
        }

        .error {
            color: #fca5a5;
            font-size: .92rem;
        }

        .footer {
            padding: 30px 0 10px;
            color: var(--muted);
            text-align: center;
        }

        @media (max-width: 900px) {
            .hero, .grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="shell">
        <nav class="nav">
            <div class="brand">
                <div class="brand-mark">GMS</div>
                <div>
                    <div>Garment Management System</div>
                    <div class="meta">Frontend + Backend</div>
                </div>
            </div>
            <div class="nav-links">
                <a href="{{ route('home') }}">Home</a>
                <a href="{{ route('dashboard') }}">Admin</a>
                <a href="{{ url('/api/garments') }}">API</a>
            </div>
        </nav>

        @if (session('status'))
            <div class="flash">{{ session('status') }}</div>
        @endif

        @yield('content')

        <div class="footer">
            Built with Laravel for garment inventory, storefront, and admin workflows.
        </div>
    </div>
</body>
</html>
