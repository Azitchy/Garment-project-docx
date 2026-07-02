<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Dashboard | GMS' }}</title>
    <style>
        :root {
            --bg: #f3f6fb;
            --panel: #ffffff;
            --sidebar: #0f172a;
            --sidebar-2: #111827;
            --text: #0f172a;
            --muted: #64748b;
            --line: #dbe3ef;
            --blue: #3b82f6;
            --blue-soft: #e8f1ff;
            --green: #10b981;
            --green-soft: #e6fbf4;
            --violet: #8b5cf6;
            --violet-soft: #f0eaff;
            --amber: #f59e0b;
            --amber-soft: #fff7e3;
            --rose: #ef4444;
            --rose-soft: #ffe8e8;
            --shadow: 0 10px 28px rgba(15, 23, 42, 0.08);
        }

        * { box-sizing: border-box; }
        html, body {
            height: 100%;
        }
        body {
            margin: 0;
            font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            background: linear-gradient(180deg, #edf2f7 0%, var(--bg) 100%);
            color: var(--text);
            overflow: hidden;
        }

        a { color: inherit; text-decoration: none; }
        button, input, textarea { font: inherit; }

        .app {
            height: 100vh;
            display: grid;
            grid-template-columns: 280px 1fr;
            overflow: hidden;
        }

        .sidebar {
            background: linear-gradient(180deg, var(--sidebar) 0%, var(--sidebar-2) 100%);
            color: #e2e8f0;
            padding: 20px 14px;
            display: flex;
            flex-direction: column;
            gap: 18px;
            position: sticky;
            top: 0;
            height: 100vh;
            overflow-y: auto;
            overscroll-behavior: contain;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 12px 18px;
            border-bottom: 1px solid rgba(148, 163, 184, 0.16);
        }

        .brand-mark {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            display: grid;
            place-items: center;
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            color: white;
            font-weight: 800;
            box-shadow: 0 10px 20px rgba(37, 99, 235, 0.3);
        }

        .brand strong { display: block; font-size: 1rem; }
        .brand small, .subtle, .meta { color: #94a3b8; }

        .nav-group { display: grid; gap: 8px; }

        .nav-link,
        .nav-toggle {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            padding: 13px 14px;
            border-radius: 12px;
            color: #cbd5e1;
            transition: 0.2s ease;
            background: transparent;
            border: 0;
            cursor: pointer;
            text-align: left;
        }

        .nav-link:hover,
        .nav-link.active,
        .nav-toggle:hover,
        .nav-toggle.active {
            background: #2563eb;
            color: white;
        }

        .nav-left {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .child-links {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.25s ease;
            padding-left: 18px;
        }

        .nav-section.open .child-links {
            max-height: 340px;
            padding-top: 8px;
        }

        .child-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 12px;
            margin-bottom: 6px;
            border-radius: 10px;
            color: #cbd5e1;
        }

        .child-link:hover,
        .child-link.active {
            background: rgba(37, 99, 235, 0.95);
            color: white;
        }

        .sidebar-footer {
            margin-top: auto;
            padding: 14px;
            border: 1px solid rgba(148, 163, 184, 0.12);
            background: rgba(255, 255, 255, 0.04);
            border-radius: 18px;
            color: #cbd5e1;
        }

        .main {
            min-width: 0;
            height: 100vh;
            overflow-y: auto;
            overscroll-behavior: contain;
            scrollbar-gutter: stable;
        }

        .topbar {
            height: 88px;
            background: rgba(255, 255, 255, 0.88);
            backdrop-filter: blur(18px);
            border-bottom: 1px solid var(--line);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            padding: 0 24px;
            position: sticky;
            top: 0;
            z-index: 20;
        }

        .search {
            flex: 1;
            max-width: 430px;
            display: flex;
            align-items: center;
            gap: 10px;
            background: #f8fafc;
            border: 1px solid var(--line);
            border-radius: 16px;
            padding: 12px 16px;
            color: var(--muted);
        }

        .search input {
            border: 0;
            outline: none;
            background: transparent;
            width: 100%;
            color: var(--text);
        }

        .top-actions {
            display: flex;
            align-items: center;
            gap: 18px;
        }

        .avatar {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            color: white;
            display: grid;
            place-items: center;
            font-weight: 800;
        }

        .content { padding: 22px 24px 30px; }

        .page-head {
            display: flex;
            justify-content: space-between;
            gap: 18px;
            align-items: end;
            margin-bottom: 18px;
        }

        .page-head h1 { margin: 0 0 6px; font-size: 2rem; }
        .page-head p { margin: 0; color: var(--muted); }
        .updated { color: #64748b; font-size: .95rem; }

        .grid-cards {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 16px;
            margin-bottom: 18px;
        }

        .card {
            background: var(--panel);
            border: 1px solid var(--line);
            border-radius: 18px;
            box-shadow: var(--shadow);
            padding: 18px;
            min-width: 0;
        }

        .metric {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            align-items: flex-start;
        }

        .metric h3 {
            margin: 0 0 12px;
            font-size: .92rem;
            font-weight: 500;
            color: #1f2937;
        }

        .metric strong {
            display: block;
            font-size: 1.55rem;
            line-height: 1.1;
        }

        .trend {
            display: inline-block;
            margin-top: 10px;
            font-size: .9rem;
            color: var(--muted);
        }

        .trend.up { color: var(--green); }
        .trend.down { color: var(--rose); }

        .icon-box {
            width: 38px;
            height: 38px;
            border-radius: 12px;
            display: grid;
            place-items: center;
            font-size: .75rem;
            font-weight: 800;
            flex: none;
        }

        .tone-blue { background: var(--blue-soft); color: var(--blue); }
        .tone-green { background: var(--green-soft); color: var(--green); }
        .tone-violet { background: var(--violet-soft); color: var(--violet); }
        .tone-amber { background: var(--amber-soft); color: var(--amber); }
        .tone-rose { background: var(--rose-soft); color: var(--rose); }

        .content-grid {
            display: grid;
            grid-template-columns: 1.4fr 1fr;
            gap: 16px;
        }

        .section-title {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            margin-bottom: 16px;
        }

        .section-title h2 { margin: 0; font-size: 1.05rem; }

        .pill {
            background: #e0ecff;
            color: #2563eb;
            padding: 6px 12px;
            border-radius: 999px;
            font-size: .8rem;
            font-weight: 700;
        }

        .chart-wrap { height: 330px; }

        .list { display: grid; gap: 12px; }

        .activity {
            display: flex;
            justify-content: space-between;
            gap: 16px;
            padding: 14px 0;
            border-bottom: 1px solid var(--line);
        }

        .activity:last-child { border-bottom: 0; }
        .activity strong { display: block; margin-bottom: 4px; }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table th,
        .table td {
            text-align: left;
            border-bottom: 1px solid var(--line);
            padding: 12px 10px;
            vertical-align: top;
        }

        .table th {
            color: var(--muted);
            font-size: .82rem;
            text-transform: uppercase;
            letter-spacing: .06em;
        }

        .flash {
            margin-bottom: 18px;
            padding: 14px 16px;
            border-radius: 14px;
            background: #ecfdf5;
            border: 1px solid #bbf7d0;
            color: #065f46;
        }

        .auth-panel {
            margin: 0 auto;
            width: min(460px, calc(100% - 24px));
        }

        .login-shell {
            min-height: 100vh;
            display: grid;
            place-items: center;
            background: linear-gradient(180deg, #eff6ff 0%, #f8fafc 100%);
        }

        .login-card {
            width: 100%;
            background: white;
            border: 1px solid var(--line);
            border-radius: 24px;
            box-shadow: var(--shadow);
            padding: 28px;
        }

        .field {
            display: grid;
            gap: 8px;
            margin-top: 16px;
        }

        .field input,
        .field textarea {
            border: 1px solid var(--line);
            background: #f8fafc;
            padding: 14px 16px;
            border-radius: 14px;
        }

        .button {
            border: 0;
            border-radius: 14px;
            padding: 13px 16px;
            font-weight: 700;
            cursor: pointer;
        }

        .button.primary {
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            color: white;
        }

        .button.secondary {
            background: #f1f5f9;
            color: #0f172a;
        }

        .error { color: #dc2626; font-size: .92rem; }

        .form-actions {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-top: 18px;
        }

        @media (max-width: 1100px) {
            .app { grid-template-columns: 1fr; height: auto; overflow: visible; }
            .sidebar { display: none; }
            .main { height: auto; overflow: visible; }
            .grid-cards, .content-grid { grid-template-columns: 1fr 1fr; }
        }

        @media (max-width: 768px) {
            .topbar { height: auto; padding: 14px 16px; flex-direction: column; align-items: stretch; }
            .search { max-width: none; }
            .content { padding-left: 0; padding-right: 0; }
            .grid-cards, .content-grid { grid-template-columns: 1fr; }
            .page-head { flex-direction: column; align-items: flex-start; }
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
</head>
<body>
    @php($currentUser = auth()->user())
    @php($menu = \App\Http\Controllers\AdminSectionController::menu())
    @php($activeSection = $sidebarSection ?? null)
    @php($activeSubsection = $sidebarSubsection ?? null)
    <div class="{{ request()->routeIs('login') ? 'login-shell' : 'app' }}">
        @unless(request()->routeIs('login'))
            <aside class="sidebar">
                <div class="brand">
                    <div class="brand-mark">GMS</div>
                    <div>
                        <strong>GMS</strong>
                        <small>Garment Management</small>
                    </div>
                </div>

                <nav class="nav-group" id="sidebar-menu">
                    @foreach ($menu as $key => $item)
                        @if (isset($item['children']))
                            @php($groupOpen = (isset($activeSection) && array_key_exists($activeSection, $item['children'])) || (isset($activeSubsection) && array_key_exists($activeSubsection, $item['children'])))
                            <div class="nav-section {{ $groupOpen ? 'open' : '' }}" data-group="{{ $key }}">
                                <button type="button" class="nav-toggle {{ $groupOpen ? 'active' : '' }}" data-toggle-group="{{ $key }}">
                                    <span class="nav-left"><span>{{ $item['icon'] }}</span> {{ $item['label'] }}</span>
                                    <span class="caret">▾</span>
                                </button>
                                <div class="child-links">
                                    @foreach ($item['children'] as $slug => $child)
                                        @php($childLabel = is_array($child) ? $child['label'] : $child)
                                        @php($childRoute = is_array($child) ? ($child['href'] ?? route($child['route'][0], $child['route'][1] ?? [])) : route('dashboard.section', $slug))
                                        @php($childActive = $activeSection === $slug || $activeSubsection === $slug)
                                        <a class="child-link {{ $childActive ? 'active' : '' }}" href="{{ $childRoute }}">
                                            {{ $childLabel }}
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            @php($isActive = $activeSection === $key)
                            @php($sectionRoute = isset($item['href']) ? $item['href'] : (isset($item['route']) ? route($item['route'][0], $item['route'][1] ?? []) : ($key === 'dashboard' ? route('dashboard') : route('dashboard.section', $key))))
                            <a class="nav-link {{ $isActive ? 'active' : '' }}" href="{{ $sectionRoute }}">
                                <span class="nav-left"><span>{{ $item['icon'] }}</span> {{ $item['label'] }}</span>
                            </a>
                        @endif
                    @endforeach

                </nav>

                <div class="sidebar-footer">
                    <strong>{{ $currentUser?->name ?? 'Admin User' }}</strong>
                    <div class="subtle">{{ $currentUser?->email ?? 'admin#gms.com' }}</div>
                </div>
            </aside>
        @endunless

        <main class="main">
            @if (request()->routeIs('login'))
                <div class="auth-panel">
                    @yield('content')
                </div>
            @else
                <div class="topbar">
                    <div style="display:flex;align-items:center;gap:14px;flex:1;">
                        <div style="font-size:1.2rem;color:#64748b;">☰</div>
                        <div class="search">
                            <span>⌕</span>
                            <input type="search" placeholder="Search orders, products, inventory...">
                            <span style="color:#94a3b8;">⌘K</span>
                        </div>
                    </div>

                    <div class="top-actions">
                        <div style="position:relative;color:#64748b;">🔔<span style="position:absolute;top:-6px;right:-9px;background:#ef4444;color:white;border-radius:999px;font-size:.7rem;min-width:18px;height:18px;display:grid;place-items:center;">4</span></div>
                        <div class="avatar">AU</div>
                        <div>
                            <strong style="display:block;font-size:.95rem;">{{ $currentUser?->name ?? 'Admin User' }}</strong>
                            <span class="subtle">Admin</span>
                        </div>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button class="button secondary" type="submit">Logout</button>
                        </form>
                    </div>
                </div>

                <div class="content">
                    @if (session('status'))
                        <div class="flash">{{ session('status') }}</div>
                    @endif

                    @yield('content')
                </div>
            @endif
        </main>
    </div>

    <script>
        document.querySelectorAll('[data-toggle-group]').forEach((button) => {
            button.addEventListener('click', () => {
                const group = button.closest('.nav-section');
                if (!group) return;

                const isOpen = group.classList.contains('open');
                document.querySelectorAll('.nav-section').forEach((section) => {
                    section.classList.remove('open');
                    const toggle = section.querySelector('.nav-toggle');
                    if (toggle) toggle.classList.remove('active');
                });

                if (!isOpen) {
                    group.classList.add('open');
                    button.classList.add('active');
                }
            });
        });
    </script>
</body>
</html>
