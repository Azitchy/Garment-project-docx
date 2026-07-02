@extends('layouts.admin')

@section('content')
    <div class="page-head">
        <div>
            <h1>{{ $pageMeta['title'] }}</h1>
            <p>{{ $pageMeta['subtitle'] }}</p>
        </div>
        <div class="updated">Master Data / {{ strtoupper(str_replace('-', ' ', $pageKey)) }}</div>
    </div>

    <section class="content-grid" style="margin-bottom:18px;">
        <div class="card">
            <div class="section-title">
                <h2>{{ $pageMeta['title'] }} Overview</h2>
                <a class="pill" href="{{ route('dashboard.section', 'master-data') }}">Back to Master Data</a>
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
                <h2>Master Data Snapshot</h2>
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
                            <div class="icon-box tone-{{ $item['tone'] ?? 'blue' }}">{{ $item['icon'] ?? 'MD' }}</div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="card" style="margin-bottom:18px;">
        <div class="section-title">
            <h2>{{ $masterDataOverview['compliance']['title'] }}</h2>
            <span class="pill">Based on Nepal rules</span>
        </div>
        <div class="list">
            @foreach ($masterDataOverview['compliance']['items'] as $item)
                <div class="activity">
                    <div>
                        <strong>{{ $item }}</strong>
                    </div>
                </div>
            @endforeach
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
                <h2>Master Data Pages</h2>
                <span class="pill">Quick switch</span>
            </div>
            <div class="list">
                <a class="activity" href="{{ route('dashboard.master-data.page', 'product-master') }}">
                    <div>
                        <strong>Product Master</strong>
                        <div class="subtle">SKU, sizes, colors, and NPR pricing.</div>
                    </div>
                </a>
                <a class="activity" href="{{ route('dashboard.master-data.page', 'customer-master') }}">
                    <div>
                        <strong>Customer Master</strong>
                        <div class="subtle">PAN/VAT, provinces, districts, and contact records.</div>
                    </div>
                </a>
                <a class="activity" href="{{ route('dashboard.master-data.page', 'vendor-master') }}">
                    <div>
                        <strong>Vendor Master</strong>
                        <div class="subtle">Supplier profiles, locality, and payment terms.</div>
                    </div>
                </a>
            </div>
        </div>

        <div class="card">
            <div class="section-title">
                <h2>Nepal Fit</h2>
                <span class="pill">Localized setup</span>
            </div>
            <div class="list">
                <div class="activity">
                    <div>
                        <strong>NPR pricing and billing</strong>
                        <div class="subtle">Use Nepal Rupees for local business operations.</div>
                    </div>
                </div>
                <div class="activity">
                    <div>
                        <strong>PAN/VAT ready records</strong>
                        <div class="subtle">Store the tax details needed for compliant customer and vendor setup.</div>
                    </div>
                </div>
                <div class="activity">
                    <div>
                        <strong>Nepali address hierarchy</strong>
                        <div class="subtle">Province, district, municipality, and ward fields improve delivery and record keeping.</div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
