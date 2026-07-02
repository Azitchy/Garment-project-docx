@extends('layouts.admin')

@section('content')
    <div class="page-head">
        <div>
            <h1>{{ $sectionMeta['title'] }}</h1>
            <p>{{ $sectionMeta['subtitle'] }}</p>
        </div>
        <div class="updated">Section: {{ strtoupper(str_replace('-', ' ', $sectionKey)) }}</div>
    </div>

    <section class="grid-cards" style="grid-template-columns:repeat(3,minmax(0,1fr));">
        @foreach ($highlights as $item)
            <div class="card">
                <div class="metric">
                    <div>
                        <h3>{{ $item['label'] }}</h3>
                        <strong>{{ $item['value'] }}</strong>
                    </div>
                    <div class="icon-box tone-{{ $item['tone'] ?? 'blue' }}">{{ $item['icon'] ?? 'SE' }}</div>
                </div>
            </div>
        @endforeach
    </section>

    @if ($sectionKey === 'inventory' && !empty($inventoryOverview))
        <section class="card" style="margin-bottom:18px;">
            <div class="section-title">
                <div>
                    <h2>Inventory Suite</h2>
                    <p class="subtle" style="margin:6px 0 0;">A complete control center for stock flow, reorder logic, and management reporting.</p>
                </div>
                <span class="pill">Marketplace ready</span>
            </div>

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

            <div class="content-grid" style="margin-bottom:18px;">
                <div class="card" style="box-shadow:none;">
                    <div class="section-title">
                        <h2>{{ $inventoryOverview['flow']['title'] }}</h2>
                        <span class="pill">Fulfillment path</span>
                    </div>
                    <p class="subtle" style="margin-top:0;">{{ $inventoryOverview['flow']['subtitle'] }}</p>
                    <div class="list">
                        @foreach ($inventoryOverview['flow']['steps'] as $step)
                            <div class="activity" style="padding:10px 0;">
                                <div>
                                    <strong>{{ $step['label'] }}</strong>
                                    <div class="subtle">{{ $step['meta'] }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="card" style="box-shadow:none;">
                    <div class="section-title">
                        <h2>{{ $inventoryOverview['monitoring']['title'] }}</h2>
                        <span class="pill">Live stock watch</span>
                    </div>
                    <div class="list">
                        @foreach ($inventoryOverview['monitoring']['items'] as $item)
                            <div class="activity" style="padding:10px 0;">
                                <div>
                                    <strong>{{ $item }}</strong>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="content-grid">
                <div class="card" style="box-shadow:none;">
                    <div class="section-title">
                        <h2>{{ $inventoryOverview['reorder']['title'] }}</h2>
                        <span class="pill">Automation</span>
                    </div>
                    <div class="list">
                        @foreach ($inventoryOverview['reorder']['items'] as $item)
                            <div class="activity" style="padding:10px 0;">
                                <div>
                                    <strong>{{ $item }}</strong>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="card" style="box-shadow:none;">
                    <div class="section-title">
                        <h2>{{ $inventoryOverview['reports']['title'] }}</h2>
                        <span class="pill">Management view</span>
                    </div>
                    <div class="list">
                        @foreach ($inventoryOverview['reports']['items'] as $item)
                            <div class="activity" style="padding:10px 0;">
                                <div>
                                    <strong>{{ $item }}</strong>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div style="display:flex;flex-wrap:wrap;gap:10px;margin-top:18px;">
                @foreach ($inventoryOverview['quickActions'] as $action)
                    <a class="pill" href="{{ route('dashboard.inventory.page', $action['target']) }}">{{ $action['label'] }}</a>
                @endforeach
            </div>
        </section>
    @endif

    @if ($sectionKey === 'orders' && !empty($ordersOverview))
        <section class="card" style="margin-bottom:18px;">
            <div class="section-title">
                <div>
                    <h2>Orders Suite</h2>
                    <p class="subtle" style="margin:6px 0 0;">Domestic and export order flow with sample tracking and delivery control for Nepal operations.</p>
                </div>
                <span class="pill">Workflow ready</span>
            </div>

            <section class="grid-cards" style="grid-template-columns:repeat(4,minmax(0,1fr));margin-bottom:18px;">
                @foreach ($ordersOverview['stats'] as $stat)
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

            <div class="content-grid" style="margin-bottom:18px;">
                @foreach ($ordersOverview['sections'] as $block)
                    <div class="card" style="box-shadow:none;">
                        <div class="section-title">
                            <h2>{{ $block['title'] }}</h2>
                            <span class="pill">Core workflow</span>
                        </div>
                        <div class="list">
                            @foreach ($block['items'] as $item)
                                <div class="activity" style="padding:10px 0;">
                                    <div>
                                        <strong>{{ $item }}</strong>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>

            <div style="display:flex;flex-wrap:wrap;gap:10px;margin-top:18px;">
                @foreach ($ordersOverview['quickActions'] as $action)
                    <a class="pill" href="{{ route('dashboard.orders.page', $action['target']) }}">{{ $action['label'] }}</a>
                @endforeach
            </div>
        </section>
    @endif

    @if ($sectionKey === 'hr-payroll' && !empty($hrPayrollOverview))
        <section class="card" style="margin-bottom:18px;">
            <div class="section-title">
                <div>
                    <h2>HR & Payroll Suite</h2>
                    <p class="subtle" style="margin:6px 0 0;">A structured people operations module for employee control, attendance, leave, and payroll.</p>
                </div>
                <span class="pill">Organization ready</span>
            </div>

            <section class="grid-cards" style="grid-template-columns:repeat(4,minmax(0,1fr));margin-bottom:18px;">
                @foreach ($hrPayrollOverview['stats'] as $stat)
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

            <div class="content-grid" style="margin-bottom:18px;">
                @foreach ($hrPayrollOverview['sections'] as $block)
                    <div class="card" style="box-shadow:none;">
                        <div class="section-title">
                            <h2>{{ $block['title'] }}</h2>
                            <span class="pill">Core function</span>
                        </div>
                        <div class="list">
                            @foreach ($block['items'] as $item)
                                <div class="activity" style="padding:10px 0;">
                                    <div>
                                        <strong>{{ $item }}</strong>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>

            <div style="display:flex;flex-wrap:wrap;gap:10px;margin-top:18px;">
                @foreach ($hrPayrollOverview['quickActions'] as $action)
                    <a class="pill" href="{{ route('dashboard.hr-payroll.page', $action['target']) }}">{{ $action['label'] }}</a>
                @endforeach
            </div>
        </section>
    @endif

    @if ($sectionKey === 'finance' && !empty($financeOverview))
        <section class="card" style="margin-bottom:18px;">
            <div class="section-title">
                <div>
                    <h2>Finance Suite</h2>
                    <p class="subtle" style="margin:6px 0 0;">A complete accounting and cash control center for daily operations and monthly reporting.</p>
                </div>
                <span class="pill">Organization ready</span>
            </div>

            <section class="grid-cards" style="grid-template-columns:repeat(4,minmax(0,1fr));margin-bottom:18px;">
                @foreach ($financeOverview['stats'] as $stat)
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

            <div class="content-grid" style="margin-bottom:18px;">
                @foreach ($financeOverview['sections'] as $block)
                    <div class="card" style="box-shadow:none;">
                        <div class="section-title">
                            <h2>{{ $block['title'] }}</h2>
                            <span class="pill">Core function</span>
                        </div>
                        <div class="list">
                            @foreach ($block['items'] as $item)
                                <div class="activity" style="padding:10px 0;">
                                    <div>
                                        <strong>{{ $item }}</strong>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>

            <div style="display:flex;flex-wrap:wrap;gap:10px;margin-top:18px;">
                @foreach ($financeOverview['quickActions'] as $action)
                    <a class="pill" href="{{ route('dashboard.finance.page', $action['target']) }}">{{ $action['label'] }}</a>
                @endforeach
            </div>
        </section>
    @endif

    <section class="card">
        <div class="section-title">
            <h2>{{ $sectionMeta['title'] }} Records</h2>
            <a class="pill" href="{{ route('dashboard.section.create', $sectionKey) }}">Create New</a>
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Meta</th>
                    <th>Status</th>
                    <th>Value</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($records as $record)
                    <tr>
                        <td>
                            <strong>{{ $record->title }}</strong>
                            <div class="subtle">{{ $record->notes }}</div>
                        </td>
                        <td>{{ $record->meta }}</td>
                        <td>{{ $record->status }}</td>
                        <td>{{ $record->value }}</td>
                        <td>
                            <a class="button secondary" href="{{ route('dashboard.section.edit', [$sectionKey, $record]) }}">Edit</a>
                            <form action="{{ route('dashboard.section.destroy', [$sectionKey, $record]) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button class="button secondary" type="submit" onclick="return confirm('Delete this record?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">No records found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div style="margin-top:18px;">
            {{ $records->links() }}
        </div>
    </section>

@endsection
