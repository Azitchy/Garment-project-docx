<?php

namespace App\Http\Controllers;

use App\Models\DashboardRecord;
use App\Models\Garment;
use Carbon\Carbon;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    public function index(): View
    {
        $garments = Garment::query()->latest()->get();
        $inventoryValue = $garments->sum(fn (Garment $garment): float => (float) $garment->price * (int) $garment->stock);
        $inventoryRecords = DashboardRecord::query()->where('section', 'inventory');

        return view('admin.dashboard', [
            'sidebarSection' => 'dashboard',
            'lastUpdated' => Carbon::now()->format('M d, Y H:i'),
            'metrics' => [
                ['label' => 'Active Orders', 'value' => 3, 'trend' => '+12% vs last month', 'trendClass' => 'up', 'tone' => 'blue', 'icon' => 'AO'],
                ['label' => 'Total Revenue', 'value' => '$369,900.00', 'trend' => '+8.5% vs last month', 'trendClass' => 'up', 'tone' => 'green', 'icon' => '$'],
                ['label' => 'Production Efficiency', 'value' => '78.8%', 'trend' => '+3.2% vs last month', 'trendClass' => 'up', 'tone' => 'violet', 'icon' => 'PE'],
                ['label' => 'Quality Pass Rate', 'value' => '80.0%', 'trend' => '-1.5% vs last month', 'trendClass' => 'down', 'tone' => 'violet', 'icon' => 'QC'],
                ['label' => 'Inventory Value', 'value' => '$' . number_format($inventoryValue, 2), 'trend' => 'Based on current stock', 'trendClass' => 'flat', 'tone' => 'amber', 'icon' => 'IV'],
                ['label' => 'Total Employees', 'value' => '10', 'trend' => 'Admin team overview', 'trendClass' => 'flat', 'tone' => 'blue', 'icon' => 'HR'],
                ['label' => 'Pending Shipments', 'value' => '1', 'trend' => 'Awaiting dispatch', 'trendClass' => 'flat', 'tone' => 'blue', 'icon' => 'PS'],
                ['label' => 'Pending Invoices', 'value' => '$94,872.00', 'trend' => 'Finance queue', 'trendClass' => 'flat', 'tone' => 'rose', 'icon' => 'PI'],
            ],
            'productionLabels' => ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
            'productionPlanned' => [800, 700, 500, 600, 800, 900],
            'productionActual' => [720, 650, 420, 540, 760, 810],
            'orderStatusLabels' => ['In Production', 'Pending', 'Completed', 'Draft'],
            'orderStatusValues' => [3, 3, 0, 1],
            'latestGarments' => $garments->take(5),
            'inventoryFeatures' => [
                [
                    'title' => 'Product Registration',
                    'description' => 'Add products, assign SKU/barcode, and store category, supplier, pricing, and unit details.',
                    'link' => route('admin.inventory-records.index', ['type' => 'product-registration']),
                    'count' => $inventoryRecords->clone()->where('record_type', 'product-registration')->count(),
                ],
                [
                    'title' => 'Stock In',
                    'description' => 'Receive supplier inventory, save invoices, and increase stock automatically.',
                    'link' => route('admin.inventory-records.index', ['type' => 'stock-in']),
                    'count' => $inventoryRecords->clone()->where('record_type', 'stock-in')->count(),
                ],
                [
                    'title' => 'Stock Out',
                    'description' => 'Issue inventory for sales or internal use while preventing negative stock if needed.',
                    'link' => route('admin.inventory-records.index', ['type' => 'stock-out']),
                    'count' => $inventoryRecords->clone()->where('record_type', 'stock-out')->count(),
                ],
                [
                    'title' => 'Real-Time Tracking',
                    'description' => 'Track available, reserved, and damaged quantities with instant stock visibility.',
                    'link' => route('admin.inventory-records.index', ['type' => 'real-time-tracking']),
                    'count' => $inventoryRecords->clone()->where('record_type', 'real-time-tracking')->count(),
                ],
                [
                    'title' => 'Barcode / QR Support',
                    'description' => 'Use scanner-friendly entry to reduce manual errors and speed up operations.',
                    'link' => route('admin.inventory-records.index', ['type' => 'barcode-qr-support']),
                    'count' => $inventoryRecords->clone()->where('record_type', 'barcode-qr-support')->count(),
                ],
                [
                    'title' => 'Supplier & Customer Management',
                    'description' => 'Maintain parties, contacts, histories, and purchase or sales records.',
                    'link' => route('admin.inventory-records.index', ['type' => 'supplier-management']),
                    'count' => $inventoryRecords->clone()->whereIn('record_type', ['supplier-management', 'customer-management'])->count(),
                ],
                [
                    'title' => 'Purchases, Sales, Transfers',
                    'description' => 'Manage purchase orders, invoices, returns, refunds, and warehouse transfers.',
                    'link' => route('admin.inventory-records.index', ['type' => 'purchase-management']),
                    'count' => $inventoryRecords->clone()->whereIn('record_type', ['purchase-management', 'sales-management', 'inventory-transfers'])->count(),
                ],
                [
                    'title' => 'Adjustments, Alerts, Reports',
                    'description' => 'Fix stock after counts, trigger low-stock alerts, and review analytics.',
                    'link' => route('admin.inventory-records.index', ['type' => 'stock-adjustments']),
                    'count' => $inventoryRecords->clone()->whereIn('record_type', ['stock-adjustments', 'low-stock-alerts', 'reports-analytics'])->count(),
                ],
            ],
            'recentActivity' => [
                ['title' => 'New order batch received', 'meta' => '14 mins ago'],
                ['title' => 'Quality review completed', 'meta' => '52 mins ago'],
                ['title' => 'Low stock alert on denim jacket', 'meta' => '2 hours ago'],
                ['title' => 'Invoice queued for approval', 'meta' => 'Today, 09:30'],
            ],
        ]);
    }
}
