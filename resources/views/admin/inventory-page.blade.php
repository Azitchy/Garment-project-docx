@extends('layouts.admin')

@section('content')
    <div class="page-head">
        <div>
            <h1>{{ $pageMeta['title'] }}</h1>
            <p>{{ $pageMeta['subtitle'] }}</p>
        </div>
        <div class="updated">Inventory / {{ strtoupper(str_replace('-', ' ', $pageKey)) }}</div>
    </div>

    <section class="card" style="margin-bottom:18px;">
        <div class="section-title">
            <div>
                <h2>{{ $pageMeta['title'] }}</h2>
                <p class="subtle" style="margin:6px 0 0;">{{ $pageMeta['banner'] }}</p>
            </div>
            <span class="pill">{{ $crudType ? 'CRUD ready' : 'Module detail' }}</span>
        </div>

        <p class="subtle" style="margin-top:0;">{{ $inventoryCalculation }}</p>

        <section class="grid-cards" style="grid-template-columns:repeat(4,minmax(0,1fr));margin-bottom:18px;">
            @foreach ($inventoryMetrics as $item)
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
        </section>
    </section>

    <section class="card" style="margin-bottom:18px;">
        <div class="section-title">
            <h2>Connected Actions</h2>
            <span class="pill">Navigation</span>
        </div>
        <div style="display:flex;flex-wrap:wrap;gap:10px;">
            @if ($crudType)
                <a class="pill" href="{{ route('admin.inventory-records.create', ['type' => $crudType]) }}">Create {{ $pageMeta['title'] }}</a>
                <a class="pill" href="{{ route('admin.inventory-records.index', ['type' => $crudType]) }}">View {{ $pageMeta['title'] }} Records</a>
            @endif
            <a class="pill" href="{{ route('admin.inventory-records.index', isset($pageMeta['crudType']) ? ['type' => $pageMeta['crudType']] : []) }}">Open {{ $pageMeta['title'] }} CRUD</a>
            <a class="pill" href="{{ route('admin.inventory-records.index') }}">Open All Inventory CRUD</a>
            @foreach ($pageMeta['actions'] as $label => $url)
                <a class="pill" href="{{ $url }}">{{ $label }}</a>
            @endforeach
        </div>
    </section>

    <section class="card">
        <div class="section-title">
            <div>
                <h2>{{ $pageMeta['title'] }} Records</h2>
                <p class="subtle" style="margin:6px 0 0;">Manage the live entries for this inventory module directly from here.</p>
            </div>
            @if ($crudType)
                <a class="pill" href="{{ route('admin.inventory-records.create', ['type' => $crudType]) }}">Add Record</a>
            @endif
        </div>

        <div style="overflow:auto;">
            <table class="table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Party</th>
                        <th>Qty / Value</th>
                        <th>Status</th>
                        <th>Updated</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($records as $record)
                        <tr>
                            <td>
                                <strong>{{ $record->title }}</strong>
                                <div class="subtle">{{ $record->sku ?: $record->barcode ?: 'No code' }}</div>
                            </td>
                            <td>
                                <div>{{ $record->supplier ?: $record->customer ?: $record->warehouse ?: '-' }}</div>
                                <div class="subtle">{{ $record->contact_name ?: $record->reference_no ?: '-' }}</div>
                            </td>
                            <td>
                                <div>Qty: {{ $record->quantity ?? '-' }}</div>
                                <div class="subtle">
                                    Value:
                                    @if ($record->cost_price !== null && $record->quantity !== null)
                                        Rs. {{ number_format((float) $record->cost_price * (int) $record->quantity, 2) }}
                                    @elseif ($record->selling_price !== null && $record->quantity !== null)
                                        Rs. {{ number_format((float) $record->selling_price * (int) $record->quantity, 2) }}
                                    @else
                                        -
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div>{{ $record->status ?: ($record->transfer_status ?: 'Active') }}</div>
                                <div class="subtle">{{ $record->is_active ? 'Active' : 'Inactive' }}</div>
                            </td>
                            <td>{{ optional($record->updated_at)->format('M d, Y') ?? '-' }}</td>
                            <td>
                                <a class="button secondary" href="{{ route('admin.inventory-records.edit', $record) }}">Edit</a>
                                <form action="{{ route('admin.inventory-records.destroy', $record) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button class="button secondary" type="submit" onclick="return confirm('Delete this inventory record?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">No records found for this inventory section.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="margin-top:18px;">
            {{ $records->links() }}
        </div>
    </section>
@endsection
