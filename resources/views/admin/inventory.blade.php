@extends('layouts.admin')

@section('content')
    <div class="page-head">
        <div>
            <h1>{{ $sectionMeta['title'] }}</h1>
            <p>{{ $sectionMeta['subtitle'] }}</p>
        </div>
        <div class="updated">Section: INVENTORY</div>
    </div>

    <section class="card" style="margin-bottom:18px;">
        <div class="section-title">
            <div>
                <h2>{{ $inventoryOverview['hero']['title'] }}</h2>
                <p class="subtle" style="margin:6px 0 0;">{{ $inventoryOverview['hero']['subtitle'] }}</p>
            </div>
            <span class="pill">New inventory suite</span>
        </div>

        <p class="subtle" style="margin-top:0;">{{ $inventoryOverview['hero']['banner'] }}</p>

        <section class="grid-cards" style="grid-template-columns:repeat(4,minmax(0,1fr));margin-bottom:18px;">
            @foreach ($inventoryOverview['stats'] as $stat)
                <div class="card" style="box-shadow:none;">
                    <div class="metric">
                        <div>
                            <h3>{{ $stat['label'] }}</h3>
                            <strong>{{ $stat['value'] }}</strong>
                        </div>
                    </div>
                </div>
            @endforeach
        </section>

        <div class="content-grid">
            <div class="card" style="box-shadow:none;">
                <div class="section-title">
                    <h2>Core Modules</h2>
                    <span class="pill">Module grid</span>
                </div>
                <div class="grid-cards" style="grid-template-columns:repeat(2,minmax(0,1fr));margin-bottom:0;">
                    @foreach ($inventoryOverview['modules'] as $module)
                        <div class="card" style="box-shadow:none;">
                            <h3 style="margin-top:0;">{{ $module['title'] }}</h3>
                            <p class="subtle" style="margin-bottom:0;">{{ $module['summary'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="card" style="box-shadow:none;">
                <div class="section-title">
                    <h2>Quick Actions</h2>
                    <span class="pill">Jump in</span>
                </div>
                <div class="list">
                    <a class="activity" href="{{ route('admin.inventory-records.index') }}">
                        <div>
                            <strong>Inventory CRUD</strong>
                            <div class="subtle">Create, edit, and delete inventory records.</div>
                        </div>
                    </a>
                    @foreach ($inventoryOverview['quickActions'] as $action)
                        <a class="activity" href="{{ route('dashboard.inventory.page', $action['target']) }}">
                            <div>
                                <strong>{{ $action['label'] }}</strong>
                                <div class="subtle">Open this inventory workflow</div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <section class="card">
        <div class="section-title">
            <h2>What the suite supports</h2>
            <span class="pill">Feature set</span>
        </div>
        <div class="grid-cards" style="grid-template-columns:repeat(3,minmax(0,1fr));">
            @foreach ($inventoryOverview['modules'] as $module)
                <div class="card" style="box-shadow:none;">
                    <h3 style="margin-top:0;">{{ $module['title'] }}</h3>
                    <p class="subtle" style="margin-bottom:0;">{{ $module['summary'] }}</p>
                </div>
            @endforeach
        </div>
    </section>
@endsection
