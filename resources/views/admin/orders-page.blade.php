@extends('layouts.admin')

@section('content')
    <div class="page-head">
        <div>
            <h1>{{ $pageMeta['title'] }}</h1>
            <p>{{ $pageMeta['subtitle'] }}</p>
        </div>
        <div class="updated">Orders / {{ strtoupper(str_replace('-', ' ', $pageKey)) }}</div>
    </div>

    <section class="content-grid" style="margin-bottom:18px;">
        <div class="card">
            <div class="section-title">
                <h2>{{ $pageMeta['title'] }} Overview</h2>
                <a class="pill" href="{{ route('dashboard.section', 'orders') }}">Back to Orders</a>
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
                <h2>Orders Snapshot</h2>
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
                            <div class="icon-box tone-{{ $item['tone'] ?? 'blue' }}">{{ $item['icon'] ?? 'OR' }}</div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="card" style="margin-bottom:18px;">
        <div class="section-title">
            <h2>{{ $ordersOverview['compliance']['title'] }}</h2>
            <span class="pill">Based on Nepal rules</span>
        </div>
        <div class="list">
            @foreach ($ordersOverview['compliance']['items'] as $item)
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
                <h2>Orders Pages</h2>
                <span class="pill">Quick switch</span>
            </div>
            <div class="list">
                <a class="activity" href="{{ route('dashboard.orders.page', 'sales-order-workflow') }}">
                    <div>
                        <strong>Sales Order Workflow</strong>
                        <div class="subtle">Quotation, order creation, and delivery flow.</div>
                    </div>
                </a>
                <a class="activity" href="{{ route('dashboard.orders.page', 'order-fulfillment') }}">
                    <div>
                        <strong>Order Fulfillment</strong>
                        <div class="subtle">Picking, packing, dispatch, and closure.</div>
                    </div>
                </a>
                <a class="activity" href="{{ route('dashboard.orders.page', 'sample-workflow') }}">
                    <div>
                        <strong>Sample Workflow</strong>
                        <div class="subtle">Request, approval, revision, and closure.</div>
                    </div>
                </a>
                <a class="activity" href="{{ route('dashboard.orders.page', 'reports') }}">
                    <div>
                        <strong>Order Reports</strong>
                        <div class="subtle">Open orders, samples, delivery performance, and region sales.</div>
                    </div>
                </a>
            </div>
        </div>

        <div class="card">
            <div class="section-title">
                <h2>Nepal Fit</h2>
                <span class="pill">Localized workflow</span>
            </div>
            <div class="list">
                <div class="activity">
                    <div>
                        <strong>NPR pricing for domestic orders</strong>
                        <div class="subtle">Keep local customer pricing and order values in Nepal Rupees.</div>
                    </div>
                </div>
                <div class="activity">
                    <div>
                        <strong>Domestic and export tracking</strong>
                        <div class="subtle">Separate local sales from cross-border order handling.</div>
                    </div>
                </div>
                <div class="activity">
                    <div>
                        <strong>Delivery details for Nepal addresses</strong>
                        <div class="subtle">Use district, municipality, and ward information for accurate dispatch.</div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
