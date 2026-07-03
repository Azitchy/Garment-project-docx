<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DashboardRecord;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class InventoryRecordController extends Controller
{
    private const TYPES = [
        'product-registration' => 'Product Registration',
        'stock-in' => 'Stock In',
        'stock-out' => 'Stock Out',
        'real-time-tracking' => 'Real-Time Stock Tracking',
        'barcode-qr-support' => 'Barcode / QR Support',
        'supplier-management' => 'Supplier Management',
        'customer-management' => 'Customer Management',
        'purchase-management' => 'Purchase Management',
        'sales-management' => 'Sales Management',
        'inventory-transfers' => 'Inventory Transfers',
        'stock-adjustments' => 'Stock Adjustments',
        'low-stock-alerts' => 'Low Stock Alerts',
        'reports-analytics' => 'Reports & Analytics',
    ];

    public function index(Request $request): View
    {
        $type = $request->string('type')->toString();
        if ($type !== '' && ! array_key_exists($type, self::TYPES)) {
            abort(404);
        }

        $records = DashboardRecord::query()
            ->where('section', 'inventory')
            ->when($type !== '', fn ($query) => $query->where('record_type', $type))
            ->latest()
            ->paginate(12)
            ->withQueryString();

        $stats = [
            'products' => DashboardRecord::query()->where('section', 'inventory')->where('record_type', 'product-registration')->count(),
            'stockIn' => DashboardRecord::query()->where('section', 'inventory')->where('record_type', 'stock-in')->count(),
            'stockOut' => DashboardRecord::query()->where('section', 'inventory')->where('record_type', 'stock-out')->count(),
            'alerts' => DashboardRecord::query()->where('section', 'inventory')->where('record_type', 'low-stock-alerts')->count(),
        ];

        return view('admin.inventory-records.index', [
            'sidebarSection' => 'inventory',
            'sidebarSubsection' => 'inventory-admin',
            'sectionKey' => 'inventory',
            'type' => $type,
            'typeOptions' => self::TYPES,
            'records' => $records,
            'stats' => $stats,
            'recordCount' => DashboardRecord::query()->where('section', 'inventory')->count(),
            'inventorySummary' => $this->summary(),
        ]);
    }

    public function create(Request $request): View
    {
        $type = $request->string('type')->toString();
        if ($type !== '' && ! array_key_exists($type, self::TYPES)) {
            abort(404);
        }

        return view('admin.inventory-records.create', [
            'sidebarSection' => 'inventory',
            'sidebarSubsection' => 'inventory-admin',
            'sectionKey' => 'inventory',
            'record' => new DashboardRecord(['section' => 'inventory', 'record_type' => $type]),
            'typeOptions' => self::TYPES,
            'selectedType' => $type,
            'formMode' => 'create',
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateRecord($request);
        $data['section'] = 'inventory';

        DashboardRecord::create($data);

        return redirect()
            ->route('admin.inventory-records.index', ['type' => $data['record_type']])
            ->with('status', 'Inventory record created successfully.');
    }

    public function edit(DashboardRecord $inventory_record): View
    {
        $this->ensureInventoryRecord($inventory_record);

        return view('admin.inventory-records.edit', [
            'sidebarSection' => 'inventory',
            'sidebarSubsection' => 'inventory-admin',
            'sectionKey' => 'inventory',
            'record' => $inventory_record,
            'typeOptions' => self::TYPES,
            'selectedType' => $inventory_record->record_type,
            'formMode' => 'edit',
        ]);
    }

    public function update(Request $request, DashboardRecord $inventory_record): RedirectResponse
    {
        $this->ensureInventoryRecord($inventory_record);

        $data = $this->validateRecord($request, $inventory_record->id);
        $inventory_record->update($data);

        return redirect()
            ->route('admin.inventory-records.index', ['type' => $data['record_type']])
            ->with('status', 'Inventory record updated successfully.');
    }

    public function destroy(DashboardRecord $inventory_record): RedirectResponse
    {
        $this->ensureInventoryRecord($inventory_record);

        $type = $inventory_record->record_type;
        $inventory_record->delete();

        return redirect()
            ->route('admin.inventory-records.index', ['type' => $type])
            ->with('status', 'Inventory record deleted successfully.');
    }

    private function ensureInventoryRecord(DashboardRecord $record): void
    {
        abort_unless($record->section === 'inventory', 404);
    }

    private function validateRecord(Request $request, ?int $ignoreId = null): array
    {
        $baseRules = [
            'record_type' => ['required', Rule::in(array_keys(self::TYPES))],
            'title' => ['required', 'string', 'max:255'],
            'sku' => [
                'nullable',
                'string',
                'max:120',
                Rule::unique('dashboard_records', 'sku')->ignore($ignoreId),
            ],
            'barcode' => [
                'nullable',
                'string',
                'max:120',
                Rule::unique('dashboard_records', 'barcode')->ignore($ignoreId),
            ],
            'category' => ['nullable', 'string', 'max:120'],
            'supplier' => ['nullable', 'string', 'max:255'],
            'customer' => ['nullable', 'string', 'max:255'],
            'contact_name' => ['nullable', 'string', 'max:255'],
            'contact_phone' => ['nullable', 'string', 'max:120'],
            'contact_email' => ['nullable', 'email', 'max:255'],
            'unit' => ['nullable', 'string', 'max:60'],
            'cost_price' => ['nullable', 'numeric', 'min:0'],
            'selling_price' => ['nullable', 'numeric', 'min:0'],
            'quantity' => ['nullable', 'integer', 'min:0'],
            'reserved_quantity' => ['nullable', 'integer', 'min:0'],
            'damaged_quantity' => ['nullable', 'integer', 'min:0'],
            'warehouse' => ['nullable', 'string', 'max:120'],
            'source_warehouse' => ['nullable', 'string', 'max:120'],
            'destination_warehouse' => ['nullable', 'string', 'max:120'],
            'reference_no' => ['nullable', 'string', 'max:120'],
            'purchase_order_no' => ['nullable', 'string', 'max:120'],
            'purchase_invoice_no' => ['nullable', 'string', 'max:120'],
            'sale_invoice_no' => ['nullable', 'string', 'max:120'],
            'transfer_status' => ['nullable', 'string', 'max:120'],
            'alert_threshold' => ['nullable', 'integer', 'min:0'],
            'prevent_negative_stock' => ['nullable', 'boolean'],
            'suggested_reorder_quantity' => ['nullable', 'integer', 'min:0'],
            'adjustment_reason' => ['nullable', 'string', 'max:255'],
            'return_quantity' => ['nullable', 'integer', 'min:0'],
            'refund_amount' => ['nullable', 'numeric', 'min:0'],
            'transaction_date' => ['nullable', 'date'],
            'status' => ['nullable', 'string', 'max:120'],
            'value' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string', 'max:2000'],
            'is_active' => ['nullable', 'boolean'],
        ];

        $typeRules = match ($request->input('record_type')) {
            'product-registration' => [
                'sku' => ['required', 'string', 'max:120', Rule::unique('dashboard_records', 'sku')->ignore($ignoreId)],
                'barcode' => ['required', 'string', 'max:120', Rule::unique('dashboard_records', 'barcode')->ignore($ignoreId)],
                'category' => ['required', 'string', 'max:120'],
                'supplier' => ['required', 'string', 'max:255'],
                'unit' => ['required', 'string', 'max:60'],
                'cost_price' => ['required', 'numeric', 'min:0'],
                'selling_price' => ['required', 'numeric', 'min:0'],
                'quantity' => ['required', 'integer', 'min:0'],
                'warehouse' => ['required', 'string', 'max:120'],
            ],
            'stock-in' => [
                'supplier' => ['required', 'string', 'max:255'],
                'purchase_order_no' => ['required', 'string', 'max:120'],
                'purchase_invoice_no' => ['required', 'string', 'max:120'],
                'quantity' => ['required', 'integer', 'min:0'],
                'cost_price' => ['required', 'numeric', 'min:0'],
                'warehouse' => ['required', 'string', 'max:120'],
                'transaction_date' => ['required', 'date'],
            ],
            'stock-out' => [
                'customer' => ['required', 'string', 'max:255'],
                'sale_invoice_no' => ['required', 'string', 'max:120'],
                'quantity' => ['required', 'integer', 'min:0'],
                'selling_price' => ['required', 'numeric', 'min:0'],
                'warehouse' => ['required', 'string', 'max:120'],
                'transaction_date' => ['required', 'date'],
                'prevent_negative_stock' => ['nullable', 'boolean'],
            ],
            'supplier-management' => [
                'supplier' => ['required', 'string', 'max:255'],
                'contact_name' => ['required', 'string', 'max:255'],
                'contact_phone' => ['required', 'string', 'max:120'],
            ],
            'customer-management' => [
                'customer' => ['required', 'string', 'max:255'],
                'contact_name' => ['required', 'string', 'max:255'],
                'contact_phone' => ['required', 'string', 'max:120'],
            ],
            'purchase-management' => [
                'supplier' => ['required', 'string', 'max:255'],
                'purchase_order_no' => ['required', 'string', 'max:120'],
                'purchase_invoice_no' => ['required', 'string', 'max:120'],
                'quantity' => ['required', 'integer', 'min:0'],
                'cost_price' => ['required', 'numeric', 'min:0'],
                'transaction_date' => ['required', 'date'],
            ],
            'sales-management' => [
                'customer' => ['required', 'string', 'max:255'],
                'sale_invoice_no' => ['required', 'string', 'max:120'],
                'quantity' => ['required', 'integer', 'min:0'],
                'selling_price' => ['required', 'numeric', 'min:0'],
                'transaction_date' => ['required', 'date'],
                'return_quantity' => ['nullable', 'integer', 'min:0'],
                'refund_amount' => ['nullable', 'numeric', 'min:0'],
            ],
            'inventory-transfers' => [
                'source_warehouse' => ['required', 'string', 'max:120'],
                'destination_warehouse' => ['required', 'string', 'max:120'],
                'quantity' => ['required', 'integer', 'min:0'],
                'transfer_status' => ['required', 'string', 'max:120'],
                'transaction_date' => ['required', 'date'],
            ],
            'stock-adjustments' => [
                'adjustment_reason' => ['required', 'string', 'max:255'],
                'quantity' => ['required', 'integer', 'min:0'],
                'transaction_date' => ['required', 'date'],
            ],
            'low-stock-alerts' => [
                'alert_threshold' => ['required', 'integer', 'min:0'],
                'quantity' => ['required', 'integer', 'min:0'],
                'suggested_reorder_quantity' => ['required', 'integer', 'min:0'],
            ],
            'real-time-tracking' => [
                'quantity' => ['required', 'integer', 'min:0'],
                'reserved_quantity' => ['required', 'integer', 'min:0'],
                'damaged_quantity' => ['required', 'integer', 'min:0'],
            ],
            default => [],
        };

        $data = $request->validate(array_replace_recursive($baseRules, $typeRules));

        foreach ([
            'sku', 'barcode', 'category', 'supplier', 'customer', 'contact_name', 'contact_phone', 'contact_email',
            'unit', 'warehouse', 'source_warehouse', 'destination_warehouse', 'reference_no',
            'purchase_order_no', 'purchase_invoice_no', 'sale_invoice_no', 'transfer_status', 'adjustment_reason',
            'return_quantity', 'refund_amount', 'suggested_reorder_quantity',
        ] as $field) {
            $data[$field] = $data[$field] !== null && $data[$field] !== '' ? $data[$field] : null;
        }

        $data['prevent_negative_stock'] = (bool) ($data['prevent_negative_stock'] ?? false);

        return $data;
    }

    private function summary(): array
    {
        $products = DashboardRecord::query()->where('section', 'inventory')->where('record_type', 'product-registration')->get();
        $lowStockAlerts = DashboardRecord::query()->where('section', 'inventory')->where('record_type', 'low-stock-alerts')->get();

        return [
            ['label' => 'Total Inventory Records', 'value' => DashboardRecord::query()->where('section', 'inventory')->count()],
            ['label' => 'Products', 'value' => DashboardRecord::query()->where('section', 'inventory')->where('record_type', 'product-registration')->count()],
            ['label' => 'Movements', 'value' => DashboardRecord::query()->where('section', 'inventory')->whereIn('record_type', ['stock-in', 'stock-out', 'inventory-transfers', 'stock-adjustments'])->count()],
            ['label' => 'Alerts', 'value' => DashboardRecord::query()->where('section', 'inventory')->where('record_type', 'low-stock-alerts')->count()],
            ['label' => 'Available Stock', 'value' => $products->sum(fn (DashboardRecord $record): int => max((int) $record->quantity - (int) $record->reserved_quantity - (int) $record->damaged_quantity, 0))],
            ['label' => 'Current Inventory Value', 'value' => 'Rs. ' . number_format($products->sum(fn (DashboardRecord $record): float => (float) $record->cost_price * (int) $record->quantity), 2)],
            ['label' => 'Low Stock Triggered', 'value' => $lowStockAlerts->filter(fn (DashboardRecord $record): bool => $record->alert_threshold !== null && (int) $record->quantity <= (int) $record->alert_threshold)->count()],
        ];
    }
}
