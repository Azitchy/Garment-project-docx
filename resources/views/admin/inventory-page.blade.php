@extends('layouts.admin')

@section('content')
    <div class="page-head">
        <div>
            <h1>{{ $pageMeta['title'] }}</h1>
            <p>{{ $pageMeta['subtitle'] }}</p>
        </div>
        <div class="updated">Inventory / {{ strtoupper(str_replace('-', ' ', $pageKey)) }}</div>
    </div>

    <section class="content-grid" style="margin-bottom:18px;">
        <div class="card">
            <div class="section-title">
                <h2>{{ $pageMeta['title'] }} Overview</h2>
                <a class="pill" href="{{ route('dashboard.section', 'inventory') }}">Back to Inventory</a>
            </div>
            <p class="subtle" style="margin-top:0;">{{ $pageMeta['banner'] }}</p>

            <div class="list">
                @foreach ($pageMeta['focus'] as $item)
                    <div class="activity">
                        <div>
                            <strong>{{ $item }}</strong>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="card">
            <div class="section-title">
                <h2>Inventory Snapshot</h2>
                <span class="pill">Current state</span>
            </div>
            <div class="grid-cards" style="grid-template-columns:repeat(2,minmax(0,1fr));margin-bottom:0;">
                @foreach ($highlights as $item)
                    <div class="card" style="box-shadow:none;">
                        <div class="metric">
                            <div>
                                <h3>{{ $item['label'] }}</h3>
                                <strong>{{ $item['value'] }}</strong>
                            </div>
                            <div class="icon-box tone-{{ $item['tone'] ?? 'blue' }}">{{ $item['icon'] ?? 'IN' }}</div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="card" style="margin-bottom:18px;">
        <div class="section-title">
            <h2>Connected Actions</h2>
            <span class="pill">Navigate module</span>
        </div>
        <div style="display:flex;flex-wrap:wrap;gap:10px;">
            @foreach ($pageMeta['actions'] as $label => $url)
                <a class="pill" href="{{ $url }}">{{ $label }}</a>
            @endforeach
        </div>
    </section>

    <section class="content-grid">
        <div class="card">
            <div class="section-title">
                <h2>Inventory Suite Pages</h2>
                <span class="pill">Quick switch</span>
            </div>
            <div class="list">
                <a class="activity" href="{{ route('dashboard.inventory.page', 'sales-process') }}">
                    <div>
                        <strong>Sales Process</strong>
                        <div class="subtle">Customer order through stock reduction.</div>
                    </div>
                </a>
                <a class="activity" href="{{ route('dashboard.inventory.page', 'monitoring') }}">
                    <div>
                        <strong>Inventory Monitoring</strong>
                        <div class="subtle">Track current, reserved, available, damaged, expired, and returned stock.</div>
                    </div>
                </a>
                <a class="activity" href="{{ route('dashboard.inventory.page', 'reorder') }}">
                    <div>
                        <strong>Reorder Management</strong>
                        <div class="subtle">Trigger alerts, requests, and purchase orders from minimum levels.</div>
                    </div>
                </a>
                <a class="activity" href="{{ route('dashboard.inventory.page', 'reports') }}">
                    <div>
                        <strong>Reporting</strong>
                        <div class="subtle">Review summaries, movement, valuation, and movement trends.</div>
                    </div>
                </a>
            </div>
        </div>

        <div class="card">
            <div class="section-title">
                <h2>Module Positioning</h2>
                <span class="pill">Marketplace copy</span>
            </div>
            <div class="list">
                <div class="activity">
                    <div>
                        <strong>Built for operational clarity</strong>
                        <div class="subtle">The module keeps sales, warehouse, and purchasing aligned.</div>
                    </div>
                </div>
                <div class="activity">
                    <div>
                        <strong>Designed for demo readiness</strong>
                        <div class="subtle">Clean navigation and dedicated pages make the feature easy to present.</div>
                    </div>
                </div>
                <div class="activity">
                    <div>
                        <strong>Easy to extend</strong>
                        <div class="subtle">Additional reports or workflow steps can be added without changing the layout.</div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
