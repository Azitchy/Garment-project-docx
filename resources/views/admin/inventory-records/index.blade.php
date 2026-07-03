@extends('layouts.admin')

@section('content')
    <div class="page-head">
        <div>
            <h1>Inventory Admin</h1>
            <p>Manage products, stock flow, suppliers, customers, transfers, adjustments, alerts, and reports.</p>
        </div>
        <div class="updated">
            <a class="pill" href="{{ route('admin.inventory-records.create') }}">Add Inventory Record</a>
        </div>
    </div>

    <section class="grid-cards" style="grid-template-columns:repeat(4,minmax(0,1fr));margin-bottom:18px;">
        @foreach ($stats as $key => $count)
            <div class="card">
                <div class="metric">
                    <div>
                        <h3>{{ match ($key) {
                            'products' => 'Product Records',
                            'stockIn' => 'Stock In',
                            'stockOut' => 'Stock Out',
                            'alerts' => 'Low Stock Alerts',
                            default => ucfirst($key),
                        } }}</h3>
                        <strong>{{ $count }}</strong>
                    </div>
                </div>
            </div>
        @endforeach
    </section>

    <section class="card" style="margin-bottom:18px;">
        <div class="section-title">
            <h2>Live Inventory Calculation</h2>
            <span class="pill">Current value</span>
        </div>
        <div class="grid-cards" style="grid-template-columns:repeat(3,minmax(0,1fr));margin-bottom:0;">
            @foreach ($inventorySummary as $item)
                <div class="card" style="box-shadow:none;">
                    <div class="metric">
                        <div>
                            <h3>{{ $item['label'] }}</h3>
                            <strong>{{ $item['value'] }}</strong>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <section class="card" style="margin-bottom:18px;">
        <div class="section-title">
            <h2>Filter By Module</h2>
            <span class="pill">All CRUD areas</span>
        </div>
        <div style="display:flex;flex-wrap:wrap;gap:10px;">
            <a class="pill" href="{{ route('admin.inventory-records.index') }}">All</a>
            @foreach ($typeOptions as $key => $label)
                <a class="pill" href="{{ route('admin.inventory-records.index', ['type' => $key]) }}">{{ $label }}</a>
            @endforeach
        </div>
    </section>

    <section class="card">
        <div class="section-title">
            <div>
                <h2>Inventory Records</h2>
                <p class="subtle" style="margin:6px 0 0;">{{ $recordCount }} records in the inventory module.</p>
            </div>
            <span class="pill">{{ $type !== '' ? ($typeOptions[$type] ?? $type) : 'All Records' }}</span>
        </div>

        <div style="overflow:auto;">
            <table class="table">
                <thead>
                    <tr>
                        <th>Type</th>
                        <th>Name</th>
                        <th>SKU / Barcode</th>
                        <th>Party</th>
                        <th>Qty</th>
                        <th>Prices</th>
                        <th>Rules</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($records as $record)
                        <tr>
                            <td>{{ $typeOptions[$record->record_type] ?? $record->record_type }}</td>
                            <td>
                                <strong>{{ $record->title }}</strong>
                                <div class="subtle">{{ $record->category }}</div>
                            </td>
                            <td>
                                <div>{{ $record->sku ?: '-' }}</div>
                                <div class="subtle">{{ $record->barcode ?: '-' }}</div>
                            </td>
                            <td>
                                <strong>{{ $record->supplier ?: $record->customer ?: '-' }}</strong>
                                <div class="subtle">{{ $record->contact_name ?: $record->warehouse ?: '-' }}</div>
                            </td>
                            <td>
                                <div>{{ $record->quantity ?? '-' }}</div>
                                <div class="subtle">R: {{ $record->reserved_quantity ?? 0 }} / D: {{ $record->damaged_quantity ?? 0 }}</div>
                            </td>
                            <td>
                                <div>Cost: {{ $record->cost_price !== null ? number_format((float) $record->cost_price, 2) : '-' }}</div>
                                <div class="subtle">Sale: {{ $record->selling_price !== null ? number_format((float) $record->selling_price, 2) : '-' }}</div>
                            </td>
                            <td>
                                <div>Prevent negative: {{ $record->prevent_negative_stock ? 'Yes' : 'No' }}</div>
                                <div class="subtle">Reorder: {{ $record->suggested_reorder_quantity ?? '-' }}</div>
                                <div class="subtle">Return: {{ $record->return_quantity ?? '-' }} / Refund: {{ $record->refund_amount !== null ? number_format((float) $record->refund_amount, 2) : '-' }}</div>
                            </td>
                            <td>
                                <div>{{ $record->status ?: ($record->transfer_status ?: 'Active') }}</div>
                                <div class="subtle">{{ $record->is_active ? 'Active' : 'Inactive' }}</div>
                            </td>
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
                            <td colspan="9">No inventory records found.</td>
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
