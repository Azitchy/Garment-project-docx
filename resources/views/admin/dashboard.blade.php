@extends('layouts.admin')

@section('content')
    <div class="page-head">
        <div>
            <h1>Dashboard</h1>
            <p>Welcome back! Here's what's happening today.</p>
        </div>
        <div class="updated">Last updated: {{ $lastUpdated }}</div>
    </div>

    <section class="grid-cards">
        @foreach ($metrics as $metric)
            <div class="card">
                <div class="metric">
                    <div>
                        <h3>{{ $metric['label'] }}</h3>
                        <strong>{{ $metric['value'] }}</strong>
                        <span class="trend {{ $metric['trendClass'] }}">{{ $metric['trend'] }}</span>
                    </div>
                    <div class="icon-box tone-{{ $metric['tone'] }}">{{ $metric['icon'] }}</div>
                </div>
            </div>
        @endforeach
    </section>

    <section class="card" style="margin-top:16px;">
        <div class="section-title">
            <div>
                <h2>Inventory Features</h2>
                <p class="subtle" style="margin:6px 0 0;">All inventory changes now live in the admin CRUD flow.</p>
            </div>
            <a class="pill" href="{{ route('admin.inventory-records.index') }}">Open Inventory CRUD</a>
        </div>
        <div class="grid-cards" style="grid-template-columns:repeat(4,minmax(0,1fr));">
            @foreach ($inventoryFeatures as $feature)
                <a class="card" href="{{ $feature['link'] }}" style="display:block;">
                    <div class="metric" style="align-items:flex-start;">
                        <div>
                            <h3>{{ $feature['title'] }}</h3>
                            <strong>{{ $feature['count'] }}</strong>
                            <span class="trend flat">{{ $feature['description'] }}</span>
                        </div>
                        <div class="icon-box tone-blue">IN</div>
                    </div>
                </a>
            @endforeach
        </div>
    </section>

    <section class="content-grid">
        <div class="card">
            <div class="section-title">
                <h2>Production Overview</h2>
                <span class="pill">This Week</span>
            </div>
            <div class="chart-wrap">
                <canvas id="productionChart"></canvas>
            </div>
        </div>

        <div class="card">
            <div class="section-title">
                <h2>Order Status Distribution</h2>
                <span class="pill">All Orders</span>
            </div>
            <div class="chart-wrap">
                <canvas id="statusChart"></canvas>
            </div>
        </div>
    </section>

    @if (auth()->user()?->hasPermission('user_management_access'))
        <section class="content-grid" style="margin-top:16px;">
            <div class="card">
                <div class="section-title">
                    <h2>Role & Permission Management</h2>
                    <a class="pill" href="{{ route('admin.users.index') }}">Open Permissions</a>
                </div>
                <p class="subtle">
                    Manage admin, manager, HR, sales, inventory, accountant, and staff access from the Users screen.
                </p>
                <div class="list" style="margin-top:16px;">
                    <div class="activity">
                        <div>
                            <strong>Roles</strong>
                            <div class="subtle">Assign a role to each user account.</div>
                        </div>
                        <div class="subtle">7</div>
                    </div>
                    <div class="activity">
                        <div>
                            <strong>Permissions</strong>
                            <div class="subtle">Toggle feature-level access per user.</div>
                        </div>
                        <div class="subtle">13</div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="section-title">
                    <h2>Access Areas</h2>
                    <span class="pill">Security</span>
                </div>
                <div class="list">
                    <div class="activity">
                        <strong>Dashboard access</strong>
                        <div class="subtle">Overview and navigation entry point.</div>
                    </div>
                    <div class="activity">
                        <strong>User management access</strong>
                        <div class="subtle">Create, edit, and remove users.</div>
                    </div>
                    <div class="activity">
                        <strong>Feature access</strong>
                        <div class="subtle">Control access to inventory, finance, HR, and orders.</div>
                    </div>
                </div>
            </div>
        </section>
    @endif

    <section class="content-grid" style="margin-top:16px;">
        <div class="card">
            <div class="section-title">
                <h2>Recent Activity</h2>
                <span class="pill">Live feed</span>
            </div>
            <div class="list">
                @foreach ($recentActivity as $activity)
                    <div class="activity">
                        <div>
                            <strong>{{ $activity['title'] }}</strong>
                            <div class="subtle">{{ $activity['meta'] }}</div>
                        </div>
                        <div class="subtle">→</div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="card">
            <div class="section-title">
                <h2>Latest Garments</h2>
                <a class="pill" href="{{ route('admin.garments.index') }}">Manage inventory</a>
            </div>
            <table class="table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Stock</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($latestGarments as $garment)
                        <tr>
                            <td>{{ $garment->name }}</td>
                            <td>{{ $garment->category }}</td>
                            <td>{{ $garment->stock }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3">No garments found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    <script>
        const productionCtx = document.getElementById('productionChart');
        const statusCtx = document.getElementById('statusChart');

        new Chart(productionCtx, {
            type: 'bar',
            data: {
                labels: @json($productionLabels),
                datasets: [
                    {
                        label: 'Planned',
                        data: @json($productionPlanned),
                        backgroundColor: '#dbe4f0',
                        borderRadius: 6,
                        maxBarThickness: 28,
                    },
                    {
                        label: 'Actual',
                        data: @json($productionActual),
                        backgroundColor: '#3b82f6',
                        borderRadius: 6,
                        maxBarThickness: 28,
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                },
                scales: {
                    x: { grid: { display: false } },
                    y: { beginAtZero: true, grid: { color: '#eef2f7' } },
                },
            },
        });

        new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: @json($orderStatusLabels),
                datasets: [{
                    data: @json($orderStatusValues),
                    backgroundColor: ['#3b82f6', '#10b981', '#f59e0b', '#ef4444'],
                    borderWidth: 0,
                    hoverOffset: 4,
                }],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '65%',
                plugins: {
                    legend: {
                        position: 'right',
                        labels: {
                            usePointStyle: true,
                            pointStyle: 'circle',
                            boxWidth: 10,
                        },
                    },
                },
            },
        });
    </script>
@endsection
