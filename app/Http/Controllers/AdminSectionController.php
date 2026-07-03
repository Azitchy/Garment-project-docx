<?php

namespace App\Http\Controllers;

use App\Models\DashboardRecord;
use App\Models\Garment;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminSectionController extends Controller
{
    public function show(string $section): View
    {
        $sections = self::sections();
        abort_unless(array_key_exists($section, $sections), 404);

        if ($section === 'inventory') {
            return view('admin.inventory', [
                'sidebarSection' => 'inventory',
                'sectionKey' => 'inventory',
                'sectionMeta' => $sections[$section],
                'highlights' => $this->buildHighlights('inventory'),
                'inventoryOverview' => $this->inventoryOverview(),
            ]);
        }

        $viewData = [
            'sidebarSection' => $section,
            'sectionKey' => $section,
            'sectionMeta' => $sections[$section],
            'highlights' => $this->buildHighlights($section),
            'records' => DashboardRecord::query()
                ->where('section', $section)
                ->latest()
                ->paginate(10)
                ->withQueryString(),
        ];

        if ($section === 'hr-payroll') {
            $viewData['hrPayrollOverview'] = $this->hrPayrollOverview();
        }

        if ($section === 'finance') {
            $viewData['financeOverview'] = $this->financeOverview();
        }

        if ($section === 'master-data') {
            $viewData['masterDataOverview'] = $this->masterDataOverview();
        }

        if ($section === 'orders') {
            $viewData['ordersOverview'] = $this->ordersOverview();
        }

        return view('admin.section', $viewData);
    }

    public function inventoryPage(string $page): View
    {
        $pages = $this->inventoryPages();
        abort_unless(array_key_exists($page, $pages), 404);
        $pageMeta = $pages[$page];
        $crudType = $pageMeta['crudType'] ?? null;
        $records = $crudType !== null
            ? $this->inventoryRecordsQuery($crudType)->paginate(8)->withQueryString()
            : DashboardRecord::query()->where('section', 'inventory')->latest()->paginate(8)->withQueryString();

        return view('admin.inventory-page', [
            'sidebarSection' => 'inventory',
            'sidebarSubsection' => $page,
            'sectionKey' => 'inventory',
            'sectionMeta' => self::sections()['inventory'],
            'pageKey' => $page,
            'pageMeta' => $pageMeta,
            'highlights' => $this->buildHighlights('inventory'),
            'inventoryMetrics' => $this->inventoryModuleMetrics($page),
            'inventoryCalculation' => $this->inventoryCalculation($page),
            'inventoryOverview' => $this->inventoryOverview(),
            'records' => $records,
            'crudType' => $crudType,
        ]);
    }

    public function hrPayrollPage(string $page): View
    {
        $pages = $this->hrPayrollPages();
        abort_unless(array_key_exists($page, $pages), 404);

        return view('admin.hr-payroll-page', [
            'sidebarSection' => 'hr-payroll',
            'sidebarSubsection' => $page,
            'sectionKey' => 'hr-payroll',
            'sectionMeta' => self::sections()['hr-payroll'],
            'pageKey' => $page,
            'pageMeta' => $pages[$page],
            'highlights' => $this->buildHighlights('hr-payroll'),
            'hrPayrollOverview' => $this->hrPayrollOverview(),
            'records' => DashboardRecord::query()
                ->where('section', 'hr-payroll')
                ->latest()
                ->paginate(10)
                ->withQueryString(),
        ]);
    }

    public function financePage(string $page): View
    {
        $pages = $this->financePages();
        abort_unless(array_key_exists($page, $pages), 404);

        return view('admin.finance-page', [
            'sidebarSection' => 'finance',
            'sidebarSubsection' => $page,
            'sectionKey' => 'finance',
            'sectionMeta' => self::sections()['finance'],
            'pageKey' => $page,
            'pageMeta' => $pages[$page],
            'highlights' => $this->buildHighlights('finance'),
            'financeOverview' => $this->financeOverview(),
            'records' => DashboardRecord::query()
                ->where('section', 'finance')
                ->latest()
                ->paginate(10)
                ->withQueryString(),
        ]);
    }

    public function masterDataPage(string $page): View
    {
        $pages = $this->masterDataPages();
        abort_unless(array_key_exists($page, $pages), 404);

        return view('admin.master-data-page', [
            'sidebarSection' => 'master-data',
            'sidebarSubsection' => $page,
            'sectionKey' => 'master-data',
            'sectionMeta' => self::sections()['master-data'],
            'pageKey' => $page,
            'pageMeta' => $pages[$page],
            'highlights' => $this->buildHighlights('master-data'),
            'masterDataOverview' => $this->masterDataOverview(),
            'records' => DashboardRecord::query()
                ->where('section', 'master-data')
                ->latest()
                ->paginate(10)
                ->withQueryString(),
        ]);
    }

    public function ordersPage(string $page): View
    {
        $pages = $this->ordersPages();
        abort_unless(array_key_exists($page, $pages), 404);

        return view('admin.orders-page', [
            'sidebarSection' => 'orders',
            'sidebarSubsection' => $page,
            'sectionKey' => 'orders',
            'sectionMeta' => self::sections()['orders'],
            'pageKey' => $page,
            'pageMeta' => $pages[$page],
            'highlights' => $this->buildHighlights('orders'),
            'ordersOverview' => $this->ordersOverview(),
            'records' => DashboardRecord::query()
                ->where('section', 'orders')
                ->latest()
                ->paginate(10)
                ->withQueryString(),
        ]);
    }

    private function masterDataPages(): array
    {
        return [
            'product-master' => [
                'title' => 'Product Master',
                'subtitle' => 'Maintain Nepal-ready product records with NPR pricing and local size structure.',
                'banner' => 'Product master data should support garment styles, size variants, and local pricing in Nepal Rupees.',
                'focus' => [
                    'SKU and barcode setup',
                    'Size, color, and style variants',
                    'NPR selling price and cost price',
                    'Measurement units for fabric, pieces, and bundles',
                ],
                'actions' => [
                    'Back to Master Data Overview' => route('dashboard.section', 'master-data'),
                    'Open Products Section' => route('dashboard.section', 'products'),
                ],
            ],
            'customer-master' => [
                'title' => 'Customer Master',
                'subtitle' => 'Track Nepal customer records, tax IDs, and delivery addresses.',
                'banner' => 'Customer records should capture the details needed for local invoicing, delivery, and credit management.',
                'focus' => [
                    'PAN or VAT number',
                    'Province, district, municipality, and ward',
                    'Phone, email, and credit terms',
                    'Buyer type and account status',
                ],
                'actions' => [
                    'Back to Master Data Overview' => route('dashboard.section', 'master-data'),
                    'Open Customers Section' => route('dashboard.section', 'customers'),
                ],
            ],
            'vendor-master' => [
                'title' => 'Vendor Master',
                'subtitle' => 'Organize supplier records for fabric, trims, transport, and services.',
                'banner' => 'Vendor master records help procurement and finance work with the right supplier data every time.',
                'focus' => [
                    'Supplier category and contact details',
                    'Province, district, and locality',
                    'Bank details and payment terms',
                    'Lead time and service rating',
                ],
                'actions' => [
                    'Back to Master Data Overview' => route('dashboard.section', 'master-data'),
                    'Open Vendors Section' => route('dashboard.section', 'vendors'),
                ],
            ],
        ];
    }

    private function ordersPages(): array
    {
        return [
            'sales-order-workflow' => [
                'title' => 'Sales Order Workflow',
                'subtitle' => 'From quotation to delivery with Nepal-friendly pricing and customer data.',
                'banner' => 'Sales order records should support NPR pricing, customer addresses, and clear fulfillment steps.',
                'focus' => [
                    'Customer inquiry and quotation',
                    'Sales order creation',
                    'Advance or credit confirmation',
                    'Packing, dispatch, and delivery',
                ],
                'actions' => [
                    'Back to Orders Overview' => route('dashboard.section', 'orders'),
                    'Open Sales Orders Section' => route('dashboard.section', 'sales-orders'),
                ],
            ],
            'order-fulfillment' => [
                'title' => 'Order Fulfillment',
                'subtitle' => 'Manage picking, packing, dispatch, and delivery status.',
                'banner' => 'Fulfillment should keep warehouse and dispatch aligned so delivery updates stay accurate.',
                'focus' => [
                    'Picking and packing',
                    'Dispatch documentation',
                    'Delivery confirmation',
                    'Order closure',
                ],
                'actions' => [
                    'Back to Orders Overview' => route('dashboard.section', 'orders'),
                    'Open Inventory Stock Out' => route('dashboard.inventory.page', 'stock-out'),
                ],
            ],
            'sample-workflow' => [
                'title' => 'Sample Workflow',
                'subtitle' => 'Track sample requests, approvals, and revisions.',
                'banner' => 'Sample tracking is important in garment operations because it prevents production mistakes later on.',
                'focus' => [
                    'Sample request entry',
                    'Fit and size approval',
                    'Feedback and revision',
                    'Final sample closure',
                ],
                'actions' => [
                    'Back to Orders Overview' => route('dashboard.section', 'orders'),
                    'Open Samples Section' => route('dashboard.section', 'samples'),
                ],
            ],
            'reports' => [
                'title' => 'Order Reports',
                'subtitle' => 'Review order status, sample progress, and delivery performance.',
                'banner' => 'Management can track order throughput and spot delays across sales and production handoff.',
                'focus' => [
                    'Open order summary',
                    'Sample status report',
                    'Delivery performance',
                    'Sales by customer and region',
                ],
                'actions' => [
                    'Back to Orders Overview' => route('dashboard.section', 'orders'),
                    'Open Reports Section' => route('dashboard.section', 'reports'),
                ],
            ],
        ];
    }

    public function create(string $section): View
    {
        $sectionMeta = $this->sectionOrFail($section);

        return view('admin.section-form', [
            'sidebarSection' => $section,
            'sectionKey' => $section,
            'sectionMeta' => $sectionMeta,
            'record' => new DashboardRecord(['section' => $section]),
            'formMode' => 'create',
        ]);
    }

    public function store(Request $request, string $section): RedirectResponse
    {
        $this->sectionOrFail($section);

        $data = $this->validateRecord($request);
        $data['section'] = $section;

        DashboardRecord::create($data);

        return redirect()
            ->route('dashboard.section', $section)
            ->with('status', 'Record created successfully.');
    }

    public function edit(string $section, DashboardRecord $record): View
    {
        $this->ensureBelongsToSection($section, $record);

        return view('admin.section-form', [
            'sidebarSection' => $section,
            'sectionKey' => $section,
            'sectionMeta' => $this->sectionOrFail($section),
            'record' => $record,
            'formMode' => 'edit',
        ]);
    }

    public function update(Request $request, string $section, DashboardRecord $record): RedirectResponse
    {
        $this->ensureBelongsToSection($section, $record);

        $record->update($this->validateRecord($request));

        return redirect()
            ->route('dashboard.section', $section)
            ->with('status', 'Record updated successfully.');
    }

    public function destroy(string $section, DashboardRecord $record): RedirectResponse
    {
        $this->ensureBelongsToSection($section, $record);

        $record->delete();

        return redirect()
            ->route('dashboard.section', $section)
            ->with('status', 'Record deleted successfully.');
    }

    public static function sections(): array
    {
        return [
            'dashboard' => ['title' => 'Dashboard', 'subtitle' => 'Main control center for garment operations.'],
            'master-data' => ['title' => 'Master Data', 'subtitle' => 'Nepal-ready customer, supplier, and product records.'],
            'inventory' => ['title' => 'Inventory', 'subtitle' => 'Product registration, stock flow, barcode tools, transfers, alerts, and analytics.'],
            'products' => ['title' => 'Products', 'subtitle' => 'Product catalog and item master data.'],
            'customers' => ['title' => 'Customers', 'subtitle' => 'Customer records and buyer pipeline.'],
            'vendors' => ['title' => 'Vendors', 'subtitle' => 'Fabric, trims, and service suppliers.'],
            'orders' => ['title' => 'Orders', 'subtitle' => 'Nepal domestic and export order workflow.'],
            'sales-orders' => ['title' => 'Sales Orders', 'subtitle' => 'Customer order intake and fulfillment.'],
            'samples' => ['title' => 'Samples', 'subtitle' => 'Prototype and sampling pipeline.'],
            'stock-items' => ['title' => 'Stock Items', 'subtitle' => 'Material and finished goods stock.'],
            'stock-movements' => ['title' => 'Stock Movements', 'subtitle' => 'Issue, transfer, and receive logs.'],
            'production-lines' => ['title' => 'Production Lines', 'subtitle' => 'Line capacity and running efficiency.'],
            'daily-production' => ['title' => 'Daily Production', 'subtitle' => 'Output tracked by day and shift.'],
            'cutting' => ['title' => 'Cutting', 'subtitle' => 'Fabric cutting schedule and output.'],
            'quality-control' => ['title' => 'Quality Control', 'subtitle' => 'Inspection, defect, and pass tracking.'],
            'hr-payroll' => ['title' => 'HR & Payroll', 'subtitle' => 'Employee administration, attendance, leave, and salary control.'],
            'finance' => ['title' => 'Finance', 'subtitle' => 'Income, expenses, cash flow, payables, receivables, and reporting.'],
            'employees' => ['title' => 'Employees', 'subtitle' => 'Workforce records and roles.'],
            'attendance' => ['title' => 'Attendance', 'subtitle' => 'Daily attendance and shift check-ins.'],
            'payroll' => ['title' => 'Payroll', 'subtitle' => 'Salary processing and payment summaries.'],
            'compliance' => ['title' => 'Compliance', 'subtitle' => 'Audit, policy, and safety tracking.'],
            'procurement' => ['title' => 'Procurement', 'subtitle' => 'Purchase requests and approvals.'],
            'invoices' => ['title' => 'Invoices', 'subtitle' => 'Outgoing and pending billing documents.'],
            'expenses' => ['title' => 'Expenses', 'subtitle' => 'Operational spend and approvals.'],
            'shipments' => ['title' => 'Shipments', 'subtitle' => 'Dispatch tracking and delivery status.'],
            'reports' => ['title' => 'Reports', 'subtitle' => 'Summaries, exports, and insights.'],
            'settings' => ['title' => 'Settings', 'subtitle' => 'System preferences and access rules.'],
        ];
    }

    public static function menu(): array
    {
        return [
            'dashboard' => ['label' => 'Dashboard', 'icon' => 'D'],
            'master-data' => [
                'label' => 'Master Data',
                'icon' => 'MD',
                'children' => [
                    'master-data' => ['label' => 'Master Data Overview', 'route' => ['dashboard.section', ['master-data']]],
                    'products' => ['label' => 'Products', 'route' => ['dashboard.section', ['products']]],
                    'customers' => ['label' => 'Customers', 'route' => ['dashboard.section', ['customers']]],
                    'vendors' => ['label' => 'Vendors', 'route' => ['dashboard.section', ['vendors']]],
                ],
            ],
            'orders' => [
                'label' => 'Orders',
                'icon' => 'OR',
                'children' => [
                    'orders' => ['label' => 'Orders Overview', 'route' => ['dashboard.section', ['orders']]],
                    'sales-order-workflow' => ['label' => 'Sales Order Workflow', 'route' => ['dashboard.orders.page', ['sales-order-workflow']]],
                    'order-fulfillment' => ['label' => 'Order Fulfillment', 'route' => ['dashboard.orders.page', ['order-fulfillment']]],
                    'sample-workflow' => ['label' => 'Sample Workflow', 'route' => ['dashboard.orders.page', ['sample-workflow']]],
                    'reports' => ['label' => 'Order Reports', 'route' => ['dashboard.orders.page', ['reports']]],
                    'sales-orders' => ['label' => 'Sales Orders', 'route' => ['dashboard.section', ['sales-orders']]],
                    'samples' => ['label' => 'Samples', 'route' => ['dashboard.section', ['samples']]],
                ],
            ],
            'inventory' => [
                'label' => 'Inventory',
                'icon' => 'IN',
                'children' => [
                    'inventory' => ['label' => 'Inventory Overview', 'route' => ['dashboard.section', ['inventory']]],
                    'product-registration' => ['label' => 'Product Registration', 'route' => ['dashboard.inventory.page', ['product-registration']]],
                    'stock-in' => ['label' => 'Stock In', 'route' => ['dashboard.inventory.page', ['stock-in']]],
                    'stock-out' => ['label' => 'Stock Out', 'route' => ['dashboard.inventory.page', ['stock-out']]],
                    'real-time-tracking' => ['label' => 'Real-Time Tracking', 'route' => ['dashboard.inventory.page', ['real-time-tracking']]],
                    'barcode-qr-support' => ['label' => 'Barcode / QR', 'route' => ['dashboard.inventory.page', ['barcode-qr-support']]],
                    'supplier-management' => ['label' => 'Suppliers', 'route' => ['dashboard.inventory.page', ['supplier-management']]],
                    'customer-management' => ['label' => 'Customers', 'route' => ['dashboard.inventory.page', ['customer-management']]],
                    'purchase-management' => ['label' => 'Purchases', 'route' => ['dashboard.inventory.page', ['purchase-management']]],
                    'sales-management' => ['label' => 'Sales', 'route' => ['dashboard.inventory.page', ['sales-management']]],
                    'inventory-transfers' => ['label' => 'Transfers', 'route' => ['dashboard.inventory.page', ['inventory-transfers']]],
                    'stock-adjustments' => ['label' => 'Adjustments', 'route' => ['dashboard.inventory.page', ['stock-adjustments']]],
                    'low-stock-alerts' => ['label' => 'Low Stock Alerts', 'route' => ['dashboard.inventory.page', ['low-stock-alerts']]],
                    'reports-analytics' => ['label' => 'Reports & Analytics', 'route' => ['dashboard.inventory.page', ['reports-analytics']]],
                    'inventory-admin' => ['label' => 'Inventory CRUD', 'href' => route('admin.inventory-records.index')],
                ],
            ],
            'production' => [
                'label' => 'Production',
                'icon' => 'PR',
                'children' => [
                    'production-lines' => 'Production Lines',
                    'daily-production' => 'Daily Production',
                    'cutting' => 'Cutting',
                ],
            ],
            'hr-payroll' => [
                'label' => 'HR & Payroll',
                'icon' => 'HR',
                'children' => [
                    'hr-payroll' => ['label' => 'HR Overview', 'route' => ['dashboard.section', ['hr-payroll']]],
                    'employee-management' => ['label' => 'Employee Management', 'route' => ['dashboard.hr-payroll.page', ['employee-management']]],
                    'attendance' => ['label' => 'Attendance', 'route' => ['dashboard.hr-payroll.page', ['attendance']]],
                    'payroll' => ['label' => 'Payroll', 'route' => ['dashboard.hr-payroll.page', ['payroll']]],
                    'leave-management' => ['label' => 'Leave Management', 'route' => ['dashboard.hr-payroll.page', ['leave-management']]],
                    'performance' => ['label' => 'Performance', 'route' => ['dashboard.hr-payroll.page', ['performance']]],
                    'employees' => ['label' => 'Employees', 'route' => ['dashboard.section', ['employees']]],
                ],
            ],
            'finance' => [
                'label' => 'Finance',
                'icon' => 'FN',
                'children' => [
                    'finance' => ['label' => 'Finance Overview', 'route' => ['dashboard.section', ['finance']]],
                    'income-management' => ['label' => 'Income Management', 'route' => ['dashboard.finance.page', ['income-management']]],
                    'expense-management' => ['label' => 'Expense Management', 'route' => ['dashboard.finance.page', ['expense-management']]],
                    'purchase-payments' => ['label' => 'Purchase Payments', 'route' => ['dashboard.finance.page', ['purchase-payments']]],
                    'payroll' => ['label' => 'Payroll', 'route' => ['dashboard.finance.page', ['payroll']]],
                    'cash-bank' => ['label' => 'Cash & Bank', 'route' => ['dashboard.finance.page', ['cash-bank']]],
                    'receivables' => ['label' => 'Accounts Receivable', 'route' => ['dashboard.finance.page', ['receivables']]],
                    'payables' => ['label' => 'Accounts Payable', 'route' => ['dashboard.finance.page', ['payables']]],
                    'reconciliation' => ['label' => 'Bank Reconciliation', 'route' => ['dashboard.finance.page', ['reconciliation']]],
                    'reports' => ['label' => 'Financial Reporting', 'route' => ['dashboard.finance.page', ['reports']]],
                    'invoices' => ['label' => 'Invoices', 'route' => ['dashboard.section', ['invoices']]],
                    'expenses' => ['label' => 'Expenses', 'route' => ['dashboard.section', ['expenses']]],
                ],
            ],
            'shipments' => ['label' => 'Shipments', 'icon' => 'SH'],
            'reports' => ['label' => 'Reports', 'icon' => 'RP'],
            'permissions' => [
                'label' => 'Permissions',
                'icon' => 'PM',
                'children' => [
                    'create-user' => ['label' => 'Create User', 'href' => route('admin.users.index', ['tab' => 'create-user']) . '#access-form'],
                    'roles' => ['label' => 'Roles', 'href' => route('admin.users.index', ['tab' => 'roles']) . '#roles'],
                    'catalog' => ['label' => 'Permission Catalog', 'href' => route('admin.users.index', ['tab' => 'catalog']) . '#catalog'],
                    'users' => ['label' => 'Existing Users', 'href' => route('admin.users.index', ['tab' => 'users']) . '#users'],
                ],
            ],
        ];
    }

    private function sectionOrFail(string $section): array
    {
        $sections = self::sections();

        abort_unless(array_key_exists($section, $sections), 404);

        return $sections[$section];
    }

    private function ensureBelongsToSection(string $section, DashboardRecord $record): void
    {
        abort_unless($record->section === $section, 404);
    }

    private function buildHighlights(string $section): array
    {
        return match ($section) {
            'products' => [
                ['label' => 'Active Products', 'value' => Garment::query()->where('is_active', true)->count()],
                ['label' => 'Categories', 'value' => Garment::query()->distinct('category')->count('category')],
                ['label' => 'Low Stock', 'value' => Garment::query()->where('stock', '<=', 5)->count()],
            ],
            'customers' => [
                ['label' => 'Customers', 'value' => DashboardRecord::query()->where('section', $section)->count()],
                ['label' => 'New This Month', 'value' => 7],
                ['label' => 'Repeat Buyers', 'value' => 12],
            ],
            'vendors' => [
                ['label' => 'Approved Vendors', 'value' => DashboardRecord::query()->where('section', $section)->count()],
                ['label' => 'Open POs', 'value' => 4],
                ['label' => 'Lead Time Avg', 'value' => '7 days'],
            ],
            'master-data' => [
                ['label' => 'Products', 'value' => Garment::query()->count()],
                ['label' => 'Customers', 'value' => DashboardRecord::query()->where('section', 'customers')->count()],
                ['label' => 'Vendors', 'value' => DashboardRecord::query()->where('section', 'vendors')->count()],
            ],
            'orders' => [
                ['label' => 'Open Orders', 'value' => DashboardRecord::query()->where('section', 'sales-orders')->count()],
                ['label' => 'Samples', 'value' => DashboardRecord::query()->where('section', 'samples')->count()],
                ['label' => 'Domestic/Export', 'value' => 'Nepal market'],
            ],
            'sales-orders' => [
                ['label' => 'Open Orders', 'value' => DashboardRecord::query()->where('section', $section)->count()],
                ['label' => 'Shipped', 'value' => 18],
                ['label' => 'Backorder', 'value' => 2],
            ],
            'inventory' => [
                ['label' => 'Products Registered', 'value' => Garment::query()->count(), 'tone' => 'blue', 'icon' => 'PR'],
                ['label' => 'Available Stock', 'value' => Garment::query()->sum('stock'), 'tone' => 'green', 'icon' => 'AS'],
                ['label' => 'Reserved Stock', 'value' => 0, 'tone' => 'amber', 'icon' => 'RS'],
                ['label' => 'Damaged Stock', 'value' => 0, 'tone' => 'violet', 'icon' => 'DS'],
            ],
            'samples' => [
                ['label' => 'Sample Requests', 'value' => DashboardRecord::query()->where('section', $section)->count()],
                ['label' => 'Approved', 'value' => 2],
                ['label' => 'Pending', 'value' => 3],
            ],
            'hr-payroll' => [
                ['label' => 'Headcount', 'value' => max(12, DashboardRecord::query()->where('section', 'hr-payroll')->count() + 12)],
                ['label' => 'Attendance Rate', 'value' => '96.4%'],
                ['label' => 'Payroll Due', 'value' => '$48,250.00'],
            ],
            'finance' => [
                ['label' => 'Cash Balance', 'value' => '$124,800.00', 'tone' => 'green', 'icon' => 'CB'],
                ['label' => 'Receivables', 'value' => '$38,420.00', 'tone' => 'blue', 'icon' => 'AR'],
                ['label' => 'Payables', 'value' => '$22,150.00', 'tone' => 'amber', 'icon' => 'AP'],
            ],
            default => [
                ['label' => 'Records', 'value' => DashboardRecord::query()->where('section', $section)->count()],
                ['label' => 'Active', 'value' => DashboardRecord::query()->where('section', $section)->where('is_active', true)->count()],
                ['label' => 'Archived', 'value' => DashboardRecord::query()->where('section', $section)->where('is_active', false)->count()],
            ],
        };
    }

    private function inventoryOverview(): array
    {
        $products = $this->inventoryRecordsQuery('product-registration')->get();
        $lowStockAlerts = $this->inventoryRecordsQuery('low-stock-alerts')->get();
        $productCount = $products->isNotEmpty() ? $products->count() : Garment::query()->count();
        $availableStock = $products->isNotEmpty()
            ? $products->sum(fn (DashboardRecord $record): int => max((int) $record->quantity - (int) $record->reserved_quantity - (int) $record->damaged_quantity, 0))
            : Garment::query()->sum('stock');
        $inventoryValue = $products->isNotEmpty()
            ? $products->sum(fn (DashboardRecord $record): float => (float) $record->cost_price * (int) $record->quantity)
            : Garment::query()->get()->sum(fn (Garment $garment): float => (float) $garment->price * (int) $garment->stock);
        $lowStockCount = $lowStockAlerts->filter(fn (DashboardRecord $record): bool => $record->alert_threshold !== null && (int) $record->quantity <= (int) $record->alert_threshold)->count();

        return [
            'hero' => [
                'title' => 'Inventory Control Center',
                'subtitle' => 'Handle product registration, stock movement, supplier/customer records, alerts, and reporting in one place.',
                'banner' => 'This module replaces the old inventory flow pages with a broader operational inventory suite.',
            ],
            'stats' => [
                ['label' => 'Products Registered', 'value' => $productCount],
                ['label' => 'Available Stock', 'value' => $availableStock],
                ['label' => 'Current Inventory Value', 'value' => 'Rs. ' . number_format($inventoryValue, 2)],
                ['label' => 'Low Stock Items', 'value' => $lowStockCount],
            ],
            'modules' => [
                ['title' => 'Product Registration', 'summary' => 'Add products, assign unique SKU or barcode, and store core item details.'],
                ['title' => 'Stock In', 'summary' => 'Record goods received from suppliers and update quantities automatically.'],
                ['title' => 'Stock Out', 'summary' => 'Deduct stock when items are sold or issued and capture customer details.'],
                ['title' => 'Real-Time Tracking', 'summary' => 'Show available, reserved, and damaged stock with instant updates.'],
                ['title' => 'Barcode / QR Support', 'summary' => 'Scan items faster and reduce manual entry mistakes.'],
                ['title' => 'Supplier Management', 'summary' => 'Keep supplier records, contacts, and purchase history in one place.'],
                ['title' => 'Customer Management', 'summary' => 'Maintain buyer records and track purchase history or credit sales.'],
                ['title' => 'Purchase Management', 'summary' => 'Create purchase orders and increase stock when receipts are confirmed.'],
                ['title' => 'Sales Management', 'summary' => 'Generate invoices, handle returns, and reduce stock after sales.'],
                ['title' => 'Inventory Transfers', 'summary' => 'Move goods between warehouses or branches with transfer status tracking.'],
                ['title' => 'Stock Adjustments', 'summary' => 'Correct counts for damage, theft, expiry, or physical count differences.'],
                ['title' => 'Low Stock Alerts', 'summary' => 'Notify users at minimum thresholds and suggest reorder quantities.'],
                ['title' => 'Reports & Analytics', 'summary' => 'Review stock, sales, purchases, valuation, and movement performance.'],
            ],
            'quickActions' => [
                ['label' => 'Product Registration', 'target' => 'product-registration'],
                ['label' => 'Stock In', 'target' => 'stock-in'],
                ['label' => 'Stock Out', 'target' => 'stock-out'],
                ['label' => 'Reports & Analytics', 'target' => 'reports-analytics'],
            ],
        ];
    }

    private function inventoryModuleMetrics(string $type): array
    {
        $records = $this->inventoryRecordsQuery($type)->get();
        $count = $records->count();

        return match ($type) {
            'product-registration' => [
                ['label' => 'Products', 'value' => $count, 'tone' => 'blue', 'icon' => 'PR'],
                ['label' => 'On Hand Qty', 'value' => $records->sum(fn (DashboardRecord $record): int => (int) $record->quantity), 'tone' => 'green', 'icon' => 'QY'],
                ['label' => 'Inventory Value', 'value' => 'Rs. ' . number_format($records->sum(fn (DashboardRecord $record): float => (float) $record->cost_price * (int) $record->quantity), 2), 'tone' => 'amber', 'icon' => 'IV'],
                ['label' => 'Avg Sale Price', 'value' => $count > 0 ? 'Rs. ' . number_format($records->avg(fn (DashboardRecord $record): float => (float) $record->selling_price), 2) : 'Rs. 0.00', 'tone' => 'violet', 'icon' => 'SP'],
            ],
            'stock-in' => [
                ['label' => 'Receipts', 'value' => $count, 'tone' => 'blue', 'icon' => 'SI'],
                ['label' => 'Qty Received', 'value' => $records->sum(fn (DashboardRecord $record): int => (int) $record->quantity), 'tone' => 'green', 'icon' => 'QY'],
                ['label' => 'Received Value', 'value' => 'Rs. ' . number_format($records->sum(fn (DashboardRecord $record): float => (float) $record->cost_price * (int) $record->quantity), 2), 'tone' => 'amber', 'icon' => 'RV'],
                ['label' => 'Latest Date', 'value' => optional($records->sortByDesc('transaction_date')->first()?->transaction_date)->format('M d, Y') ?? 'N/A', 'tone' => 'violet', 'icon' => 'DT'],
            ],
            'stock-out' => [
                ['label' => 'Issues', 'value' => $count, 'tone' => 'blue', 'icon' => 'SO'],
                ['label' => 'Qty Issued', 'value' => $records->sum(fn (DashboardRecord $record): int => (int) $record->quantity), 'tone' => 'green', 'icon' => 'QY'],
                ['label' => 'Sales Value', 'value' => 'Rs. ' . number_format($records->sum(fn (DashboardRecord $record): float => (float) $record->selling_price * (int) $record->quantity), 2), 'tone' => 'amber', 'icon' => 'SV'],
                ['label' => 'Returns', 'value' => $records->sum(fn (DashboardRecord $record): int => (int) $record->return_quantity), 'tone' => 'violet', 'icon' => 'RT'],
            ],
            'real-time-tracking' => [
                ['label' => 'Tracked Items', 'value' => $count, 'tone' => 'blue', 'icon' => 'RT'],
                ['label' => 'Available', 'value' => $records->sum(fn (DashboardRecord $record): int => max((int) $record->quantity - (int) $record->reserved_quantity - (int) $record->damaged_quantity, 0)), 'tone' => 'green', 'icon' => 'AV'],
                ['label' => 'Reserved', 'value' => $records->sum(fn (DashboardRecord $record): int => (int) $record->reserved_quantity), 'tone' => 'amber', 'icon' => 'RS'],
                ['label' => 'Damaged', 'value' => $records->sum(fn (DashboardRecord $record): int => (int) $record->damaged_quantity), 'tone' => 'violet', 'icon' => 'DG'],
            ],
            'barcode-qr-support' => [
                ['label' => 'Tagged Items', 'value' => $records->whereNotNull('barcode')->count(), 'tone' => 'blue', 'icon' => 'BC'],
                ['label' => 'SKU Coverage', 'value' => $count > 0 ? round(($records->whereNotNull('sku')->count() / $count) * 100, 1) . '%' : '0%', 'tone' => 'green', 'icon' => 'SK'],
                ['label' => 'Barcode Coverage', 'value' => $count > 0 ? round(($records->whereNotNull('barcode')->count() / $count) * 100, 1) . '%' : '0%', 'tone' => 'amber', 'icon' => 'QR'],
                ['label' => 'Active Labels', 'value' => $records->where('is_active', true)->count(), 'tone' => 'violet', 'icon' => 'AL'],
            ],
            'supplier-management' => [
                ['label' => 'Suppliers', 'value' => $count, 'tone' => 'blue', 'icon' => 'SU'],
                ['label' => 'Contacts', 'value' => $records->whereNotNull('contact_phone')->count(), 'tone' => 'green', 'icon' => 'CT'],
                ['label' => 'Purchase Links', 'value' => $records->whereNotNull('purchase_order_no')->count(), 'tone' => 'amber', 'icon' => 'PO'],
                ['label' => 'Active Records', 'value' => $records->where('is_active', true)->count(), 'tone' => 'violet', 'icon' => 'AC'],
            ],
            'customer-management' => [
                ['label' => 'Customers', 'value' => $count, 'tone' => 'blue', 'icon' => 'CU'],
                ['label' => 'Contacts', 'value' => $records->whereNotNull('contact_phone')->count(), 'tone' => 'green', 'icon' => 'CT'],
                ['label' => 'Sales Links', 'value' => $records->whereNotNull('sale_invoice_no')->count(), 'tone' => 'amber', 'icon' => 'SI'],
                ['label' => 'Active Records', 'value' => $records->where('is_active', true)->count(), 'tone' => 'violet', 'icon' => 'AC'],
            ],
            'purchase-management' => [
                ['label' => 'Purchase Orders', 'value' => $count, 'tone' => 'blue', 'icon' => 'PO'],
                ['label' => 'Qty Ordered', 'value' => $records->sum(fn (DashboardRecord $record): int => (int) $record->quantity), 'tone' => 'green', 'icon' => 'QY'],
                ['label' => 'Purchase Value', 'value' => 'Rs. ' . number_format($records->sum(fn (DashboardRecord $record): float => (float) $record->cost_price * (int) $record->quantity), 2), 'tone' => 'amber', 'icon' => 'PV'],
                ['label' => 'Invoices', 'value' => $records->whereNotNull('purchase_invoice_no')->count(), 'tone' => 'violet', 'icon' => 'IN'],
            ],
            'sales-management' => [
                ['label' => 'Sales Orders', 'value' => $count, 'tone' => 'blue', 'icon' => 'SO'],
                ['label' => 'Qty Sold', 'value' => $records->sum(fn (DashboardRecord $record): int => (int) $record->quantity), 'tone' => 'green', 'icon' => 'QY'],
                ['label' => 'Sales Value', 'value' => 'Rs. ' . number_format($records->sum(fn (DashboardRecord $record): float => (float) $record->selling_price * (int) $record->quantity), 2), 'tone' => 'amber', 'icon' => 'SV'],
                ['label' => 'Refunds', 'value' => 'Rs. ' . number_format($records->sum(fn (DashboardRecord $record): float => (float) $record->refund_amount), 2), 'tone' => 'violet', 'icon' => 'RF'],
            ],
            'inventory-transfers' => [
                ['label' => 'Transfers', 'value' => $count, 'tone' => 'blue', 'icon' => 'TR'],
                ['label' => 'Qty Moved', 'value' => $records->sum(fn (DashboardRecord $record): int => (int) $record->quantity), 'tone' => 'green', 'icon' => 'QY'],
                ['label' => 'Completed', 'value' => $records->where('transfer_status', 'Completed')->count(), 'tone' => 'amber', 'icon' => 'CP'],
                ['label' => 'In Transit', 'value' => $records->where('transfer_status', 'In Transit')->count(), 'tone' => 'violet', 'icon' => 'IT'],
            ],
            'stock-adjustments' => [
                ['label' => 'Adjustments', 'value' => $count, 'tone' => 'blue', 'icon' => 'AD'],
                ['label' => 'Qty Adjusted', 'value' => $records->sum(fn (DashboardRecord $record): int => (int) $record->quantity), 'tone' => 'green', 'icon' => 'QY'],
                ['label' => 'Reasons', 'value' => $records->whereNotNull('adjustment_reason')->count(), 'tone' => 'amber', 'icon' => 'RS'],
                ['label' => 'Active Records', 'value' => $records->where('is_active', true)->count(), 'tone' => 'violet', 'icon' => 'AC'],
            ],
            'low-stock-alerts' => [
                ['label' => 'Alerts', 'value' => $count, 'tone' => 'blue', 'icon' => 'LA'],
                ['label' => 'Triggered', 'value' => $records->filter(fn (DashboardRecord $record): bool => $record->alert_threshold !== null && (int) $record->quantity <= (int) $record->alert_threshold)->count(), 'tone' => 'green', 'icon' => 'TR'],
                ['label' => 'Reorder Qty', 'value' => $records->sum(fn (DashboardRecord $record): int => (int) $record->suggested_reorder_quantity), 'tone' => 'amber', 'icon' => 'RQ'],
                ['label' => 'Critical Items', 'value' => $records->filter(fn (DashboardRecord $record): bool => $record->alert_threshold !== null && (int) $record->quantity === 0)->count(), 'tone' => 'violet', 'icon' => 'CR'],
            ],
            'reports-analytics' => [
                ['label' => 'Reports', 'value' => $count, 'tone' => 'blue', 'icon' => 'RP'],
                ['label' => 'Inventory Value', 'value' => 'Rs. ' . number_format($this->inventoryValue(), 2), 'tone' => 'green', 'icon' => 'IV'],
                ['label' => 'Movements', 'value' => $this->inventoryRecordsQuery('stock-in')->count() + $this->inventoryRecordsQuery('stock-out')->count() + $this->inventoryRecordsQuery('inventory-transfers')->count() + $this->inventoryRecordsQuery('stock-adjustments')->count(), 'tone' => 'amber', 'icon' => 'MV'],
                ['label' => 'Active Items', 'value' => $this->inventoryRecordsQuery('product-registration')->where('is_active', true)->count(), 'tone' => 'violet', 'icon' => 'AI'],
            ],
            default => [
                ['label' => 'Records', 'value' => $count, 'tone' => 'blue', 'icon' => 'IN'],
                ['label' => 'Active', 'value' => $records->where('is_active', true)->count(), 'tone' => 'green', 'icon' => 'AC'],
                ['label' => 'Archived', 'value' => $records->where('is_active', false)->count(), 'tone' => 'amber', 'icon' => 'AR'],
            ],
        };
    }

    private function inventoryCalculation(string $type): string
    {
        return match ($type) {
            'product-registration' => 'Inventory value = quantity x cost price for each product record.',
            'stock-in' => 'Receipt value = quantity x cost price for each incoming stock record.',
            'stock-out' => 'Sales value = quantity x selling price for each outgoing stock record.',
            'real-time-tracking' => 'Available stock = quantity - reserved quantity - damaged quantity.',
            'barcode-qr-support' => 'Coverage is calculated from records that already have SKU or barcode values.',
            'supplier-management' => 'Supplier and contact totals are calculated from supplier records on this page.',
            'customer-management' => 'Customer and sales totals are calculated from customer records on this page.',
            'purchase-management' => 'Purchase value = quantity x cost price for each purchase record.',
            'sales-management' => 'Net sales value = quantity x selling price minus any recorded refunds.',
            'inventory-transfers' => 'Transfer totals are calculated from quantity and transfer status on each transfer record.',
            'stock-adjustments' => 'Adjustment totals are calculated from quantity and recorded adjustment reasons.',
            'low-stock-alerts' => 'Low stock alerts are triggered when quantity is at or below the alert threshold.',
            'reports-analytics' => 'Reporting totals are calculated from the underlying product, movement, and alert records.',
            default => 'Current values are calculated from the active inventory records on this page.',
        };
    }

    private function inventoryValue(): float
    {
        $products = $this->inventoryRecordsQuery('product-registration')->get();

        if ($products->isNotEmpty()) {
            return $products->sum(fn (DashboardRecord $record): float => (float) $record->cost_price * (int) $record->quantity);
        }

        return (float) Garment::query()->get()->sum(fn (Garment $garment): float => (float) $garment->price * (int) $garment->stock);
    }

    private function inventoryRecordsQuery(?string $type = null): Builder
    {
        return DashboardRecord::query()
            ->where('section', 'inventory')
            ->when($type !== null, fn (Builder $query) => $query->where('record_type', $type))
            ->latest();
    }

    private function inventoryPages(): array
    {
        return [
            'product-registration' => [
                'title' => 'Product Registration',
                'subtitle' => 'Add products with structured item master data.',
                'crudType' => 'product-registration',
                'banner' => 'Each product should have a unique identity with SKU or barcode, category, supplier, pricing, and unit of measure.',
                'focus' => [
                    'Assign a unique SKU or barcode',
                    'Store product name and category',
                    'Link supplier details',
                    'Capture cost price, selling price, and unit',
                ],
                'actions' => [
                    'Back to Inventory Overview' => route('dashboard.section', 'inventory'),
                    'Open Stock In' => route('dashboard.inventory.page', 'stock-in'),
                ],
            ],
            'stock-in' => [
                'title' => 'Stock In',
                'subtitle' => 'Receive and record inventory from suppliers.',
                'crudType' => 'stock-in',
                'banner' => 'Inbound inventory updates stock automatically and keeps purchase paperwork attached to the receipt flow.',
                'focus' => [
                    'Record supplier receipts',
                    'Save purchase details and invoices',
                    'Increase stock quantity automatically',
                    'Keep receipt history for audits',
                ],
                'actions' => [
                    'Back to Inventory Overview' => route('dashboard.section', 'inventory'),
                    'Open Purchase Management' => route('dashboard.inventory.page', 'purchase-management'),
                ],
            ],
            'stock-out' => [
                'title' => 'Stock Out',
                'subtitle' => 'Issue stock for sales, dispatch, or internal use.',
                'crudType' => 'stock-out',
                'banner' => 'Outward stock transactions should capture the customer or issuing department and prevent negative inventory when required.',
                'focus' => [
                    'Deduct stock on sale or issue',
                    'Record customer and sales details',
                    'Prevent negative stock if desired',
                    'Track issue history and reference numbers',
                ],
                'actions' => [
                    'Back to Inventory Overview' => route('dashboard.section', 'inventory'),
                    'Open Sales Management' => route('dashboard.inventory.page', 'sales-management'),
                ],
            ],
            'real-time-tracking' => [
                'title' => 'Real-Time Stock Tracking',
                'subtitle' => 'See live inventory levels as transactions occur.',
                'crudType' => 'real-time-tracking',
                'banner' => 'The stock dashboard should instantly reflect available, reserved, damaged, and in-transit quantities across the business.',
                'focus' => [
                    'Show current inventory levels',
                    'Display available and reserved stock',
                    'Track damaged stock separately',
                    'Update instantly after every transaction',
                ],
                'actions' => [
                    'Back to Inventory Overview' => route('dashboard.section', 'inventory'),
                    'Open Stock Adjustments' => route('dashboard.inventory.page', 'stock-adjustments'),
                ],
            ],
            'barcode-qr-support' => [
                'title' => 'Barcode / QR Support',
                'subtitle' => 'Speed up entry with scanner friendly workflows.',
                'crudType' => 'barcode-qr-support',
                'banner' => 'Barcode and QR support reduces typing mistakes and makes receiving, issuing, and stock checks much faster.',
                'focus' => [
                    'Scan items for faster entry',
                    'Reduce manual errors',
                    'Accelerate receiving and sales processes',
                    'Support barcode or QR labels',
                ],
                'actions' => [
                    'Back to Inventory Overview' => route('dashboard.section', 'inventory'),
                    'Open Product Registration' => route('dashboard.inventory.page', 'product-registration'),
                ],
            ],
            'supplier-management' => [
                'title' => 'Supplier Management',
                'subtitle' => 'Manage vendors and supply-side performance.',
                'crudType' => 'supplier-management',
                'banner' => 'Supplier records should include contact details, purchase history, and service reliability for procurement decisions.',
                'focus' => [
                    'Store supplier information',
                    'Track purchase history',
                    'Manage contact details',
                    'Monitor supplier reliability',
                ],
                'actions' => [
                    'Back to Inventory Overview' => route('dashboard.section', 'inventory'),
                    'Open Purchase Management' => route('dashboard.inventory.page', 'purchase-management'),
                ],
            ],
            'customer-management' => [
                'title' => 'Customer Management',
                'subtitle' => 'Keep customer records connected to sales activity.',
                'crudType' => 'customer-management',
                'banner' => 'Customer profiles help the sales team track purchase history and manage credit sales where needed.',
                'focus' => [
                    'Maintain customer records',
                    'Track purchase history',
                    'Support credit sales if applicable',
                    'Link sales to customer accounts',
                ],
                'actions' => [
                    'Back to Inventory Overview' => route('dashboard.section', 'inventory'),
                    'Open Sales Management' => route('dashboard.inventory.page', 'sales-management'),
                ],
            ],
            'purchase-management' => [
                'title' => 'Purchase Management',
                'subtitle' => 'Plan, approve, and receive purchase orders.',
                'crudType' => 'purchase-management',
                'banner' => 'Purchase workflows should move from requisition to order to receipt while automatically increasing stock on confirmation.',
                'focus' => [
                    'Create purchase orders',
                    'Track pending and completed orders',
                    'Increase stock upon receipt',
                    'Keep invoices and order references together',
                ],
                'actions' => [
                    'Back to Inventory Overview' => route('dashboard.section', 'inventory'),
                    'Open Stock In' => route('dashboard.inventory.page', 'stock-in'),
                ],
            ],
            'sales-management' => [
                'title' => 'Sales Management',
                'subtitle' => 'Handle invoices, returns, and inventory reduction.',
                'crudType' => 'sales-management',
                'banner' => 'Sales transactions should generate invoices, handle refunds, and reduce inventory automatically after completion.',
                'focus' => [
                    'Generate sales invoices',
                    'Process returns and refunds',
                    'Automatically reduce stock after sales',
                    'Record customer details with each sale',
                ],
                'actions' => [
                    'Back to Inventory Overview' => route('dashboard.section', 'inventory'),
                    'Open Customer Management' => route('dashboard.inventory.page', 'customer-management'),
                ],
            ],
            'inventory-transfers' => [
                'title' => 'Inventory Transfers',
                'subtitle' => 'Move stock between locations with traceability.',
                'crudType' => 'inventory-transfers',
                'banner' => 'Transfers should keep warehouses or branches in sync so each location shows accurate on-hand quantities.',
                'focus' => [
                    'Move products between warehouses or branches',
                    'Track transfer status',
                    'Update both source and destination inventory',
                    'Keep transfer history for audit trails',
                ],
                'actions' => [
                    'Back to Inventory Overview' => route('dashboard.section', 'inventory'),
                    'Open Real-Time Tracking' => route('dashboard.inventory.page', 'real-time-tracking'),
                ],
            ],
            'stock-adjustments' => [
                'title' => 'Stock Adjustments',
                'subtitle' => 'Correct stock after physical verification.',
                'crudType' => 'stock-adjustments',
                'banner' => 'Adjustment records explain why stock changed so damage, theft, expiry, and count errors stay visible and controlled.',
                'focus' => [
                    'Correct inventory after physical counts',
                    'Record damage, theft, or expiry',
                    'Capture counting error explanations',
                    'Keep adjustment history for audits',
                ],
                'actions' => [
                    'Back to Inventory Overview' => route('dashboard.section', 'inventory'),
                    'Open Low Stock Alerts' => route('dashboard.inventory.page', 'low-stock-alerts'),
                ],
            ],
            'low-stock-alerts' => [
                'title' => 'Low Stock Alerts',
                'subtitle' => 'Warn the team before stockouts happen.',
                'crudType' => 'low-stock-alerts',
                'banner' => 'Minimum stock thresholds help prevent shortages and can be paired with reorder quantity suggestions.',
                'focus' => [
                    'Notify users when stock is low',
                    'Prevent stock shortages',
                    'Suggest reorder quantities',
                    'Highlight critical items early',
                ],
                'actions' => [
                    'Back to Inventory Overview' => route('dashboard.section', 'inventory'),
                    'Open Purchase Management' => route('dashboard.inventory.page', 'purchase-management'),
                ],
            ],
            'reports-analytics' => [
                'title' => 'Reports & Analytics',
                'subtitle' => 'Analyze inventory performance from multiple angles.',
                'crudType' => 'reports-analytics',
                'banner' => 'The reporting layer should support operational, financial, and movement analysis for faster decisions.',
                'focus' => [
                    'Current stock report',
                    'Sales report',
                    'Purchase report',
                    'Profit and loss report',
                    'Inventory valuation',
                    'Fast-moving and slow-moving items',
                    'Expired products',
                    'Stock adjustment history',
                ],
                'actions' => [
                    'Back to Inventory Overview' => route('dashboard.section', 'inventory'),
                    'Open Real-Time Tracking' => route('dashboard.inventory.page', 'real-time-tracking'),
                    'Open Inventory CRUD' => route('admin.inventory-records.index'),
                ],
            ],
        ];
    }

    private function hrPayrollOverview(): array
    {
        return [
            'quickActions' => [
                ['label' => 'Employee Management', 'target' => 'employee-management'],
                ['label' => 'Attendance', 'target' => 'attendance'],
                ['label' => 'Payroll', 'target' => 'payroll'],
                ['label' => 'Leave Management', 'target' => 'leave-management'],
            ],
            'stats' => [
                ['label' => 'Headcount', 'value' => max(12, DashboardRecord::query()->where('section', 'hr-payroll')->count() + 12)],
                ['label' => 'Present Today', 'value' => '11'],
                ['label' => 'On Leave', 'value' => '2'],
                ['label' => 'Payroll Due', 'value' => '$48,250.00'],
            ],
            'sections' => [
                [
                    'title' => 'Employee Administration',
                    'items' => [
                        'Employee profile records',
                        'Job title and department assignment',
                        'Employment status and contract type',
                        'Document and ID tracking',
                    ],
                ],
                [
                    'title' => 'Attendance Controls',
                    'items' => [
                        'Daily check-in/check-out',
                        'Shift planning',
                        'Late arrival tracking',
                        'Overtime and absence review',
                    ],
                ],
                [
                    'title' => 'Payroll Operations',
                    'items' => [
                        'Basic salary and allowances',
                        'Deductions and advances',
                        'Net pay calculation',
                        'Payslip generation',
                    ],
                ],
                [
                    'title' => 'HR Reporting',
                    'items' => [
                        'Headcount summary',
                        'Attendance summary',
                        'Leave summary',
                        'Payroll summary',
                    ],
                ],
            ],
        ];
    }

    private function hrPayrollPages(): array
    {
        return [
            'employee-management' => [
                'title' => 'Employee Management',
                'subtitle' => 'Organize employee records, roles, and staffing structure.',
                'banner' => 'The HR team can keep employee details, departments, and role assignments in one structured workflow.',
                'focus' => [
                    'Employee profile records',
                    'Department and designation mapping',
                    'Contract and status tracking',
                    'Document and ID management',
                ],
                'actions' => [
                    'Back to HR & Payroll Overview' => route('dashboard.section', 'hr-payroll'),
                    'Open Employees Section' => route('dashboard.section', 'employees'),
                ],
            ],
            'attendance' => [
                'title' => 'Attendance',
                'subtitle' => 'Track presence, shifts, late arrivals, and overtime.',
                'banner' => 'Attendance monitoring keeps shift operations disciplined and helps payroll stay accurate.',
                'focus' => [
                    'Check-in and check-out logging',
                    'Shift planning',
                    'Late arrival tracking',
                    'Overtime review',
                    'Absence review',
                ],
                'actions' => [
                    'Back to HR & Payroll Overview' => route('dashboard.section', 'hr-payroll'),
                    'Open Attendance Section' => route('dashboard.section', 'attendance'),
                ],
            ],
            'payroll' => [
                'title' => 'Payroll',
                'subtitle' => 'Calculate wages, deductions, advances, and net pay.',
                'banner' => 'Payroll processing becomes cleaner when earnings, deductions, and payslips are organized in one flow.',
                'focus' => [
                    'Basic salary and allowances',
                    'Deductions and advances',
                    'Net pay calculation',
                    'Payslip generation',
                    'Payroll approval',
                ],
                'actions' => [
                    'Back to HR & Payroll Overview' => route('dashboard.section', 'hr-payroll'),
                    'Open Payroll Section' => route('dashboard.section', 'payroll'),
                ],
            ],
            'leave-management' => [
                'title' => 'Leave Management',
                'subtitle' => 'Manage leave requests, balances, and absence approvals.',
                'banner' => 'Leave controls help the organization plan staffing while keeping approvals transparent.',
                'focus' => [
                    'Leave request submission',
                    'Balance tracking',
                    'Approval workflow',
                    'Absence reconciliation',
                ],
                'actions' => [
                    'Back to HR & Payroll Overview' => route('dashboard.section', 'hr-payroll'),
                    'Open Attendance Section' => route('dashboard.section', 'attendance'),
                ],
            ],
            'performance' => [
                'title' => 'Performance',
                'subtitle' => 'Support reviews, goals, and workforce development.',
                'banner' => 'Performance visibility makes it easier to review staff contribution, identify training needs, and support growth.',
                'focus' => [
                    'Review cycle scheduling',
                    'Goal tracking',
                    'Appraisal notes',
                    'Training recommendations',
                ],
                'actions' => [
                    'Back to HR & Payroll Overview' => route('dashboard.section', 'hr-payroll'),
                    'Open Compliance Section' => route('dashboard.section', 'compliance'),
                ],
            ],
        ];
    }

    private function financeOverview(): array
    {
        return [
            'quickActions' => [
                ['label' => 'Income Management', 'target' => 'income-management'],
                ['label' => 'Expense Management', 'target' => 'expense-management'],
                ['label' => 'Cash & Bank', 'target' => 'cash-bank'],
                ['label' => 'Financial Reports', 'target' => 'reports'],
            ],
            'stats' => [
                ['label' => 'Cash Balance', 'value' => '$124,800.00'],
                ['label' => 'Receivables', 'value' => '$38,420.00'],
                ['label' => 'Payables', 'value' => '$22,150.00'],
                ['label' => 'Monthly Profit', 'value' => '$67,300.00'],
            ],
            'sections' => [
                [
                    'title' => 'Income Management',
                    'items' => [
                        'Record sales or service income',
                        'Issue invoices to customers',
                        'Receive cash, bank transfer, or digital payment',
                        'Update customer balances',
                    ],
                ],
                [
                    'title' => 'Expense & Purchase Control',
                    'items' => [
                        'Record daily business expenses',
                        'Receive and verify supplier invoices',
                        'Pay suppliers and update accounts payable',
                        'Track office, fuel, travel, supplies, and marketing spend',
                    ],
                ],
                [
                    'title' => 'Cash, Bank, and Reconciliation',
                    'items' => [
                        'Record cash received and paid',
                        'Track bank deposits and withdrawals',
                        'Monitor daily cash balance',
                        'Compare cash book and bank statement',
                    ],
                ],
                [
                    'title' => 'Reporting',
                    'items' => [
                        'Profit & Loss Statement',
                        'Balance Sheet',
                        'Cash Flow Statement',
                        'Income & Expense Report',
                        'Outstanding Receivables Report',
                        'Outstanding Payables Report',
                    ],
                ],
            ],
        ];
    }

    private function masterDataOverview(): array
    {
        return [
            'compliance' => [
                'title' => 'Nepal Compliance Checklist',
                'items' => [
                    'Keep business and customer tax data ready for PAN/VAT records.',
                    'Use NPR for local pricing and invoicing.',
                    'Store province, district, municipality, and ward for Nepal addresses.',
                    'Keep product and vendor masters ready for tax invoice and delivery documents.',
                ],
            ],
            'stats' => [
                ['label' => 'Products', 'value' => Garment::query()->count()],
                ['label' => 'Customers', 'value' => DashboardRecord::query()->where('section', 'customers')->count()],
                ['label' => 'Vendors', 'value' => DashboardRecord::query()->where('section', 'vendors')->count()],
                ['label' => 'Currency', 'value' => 'NPR'],
            ],
            'sections' => [
                [
                    'title' => 'Product Master',
                    'items' => [
                        'SKU and barcode setup',
                        'Size, color, and style variants',
                        'NPR selling price and cost price',
                        'Measurement units for fabric, pieces, and bundles',
                    ],
                ],
                [
                    'title' => 'Customer Master',
                    'items' => [
                        'Customer name and buyer type',
                        'PAN or VAT number',
                        'Province, district, municipality, and ward',
                        'Phone, email, and credit terms',
                    ],
                ],
                [
                    'title' => 'Vendor Master',
                    'items' => [
                        'Supplier category and contact details',
                        'Province, district, and locality',
                        'Bank details and payment terms',
                        'Lead time and service rating',
                    ],
                ],
                [
                    'title' => 'Nepal Ready Setup',
                    'items' => [
                        'Use NPR for pricing and invoicing',
                        'Capture PAN/VAT for compliance',
                        'Store Nepali address hierarchy',
                        'Support domestic and export records',
                    ],
                ],
            ],
        ];
    }

    private function ordersOverview(): array
    {
        return [
            'quickActions' => [
                ['label' => 'Sales Order Workflow', 'target' => 'sales-order-workflow'],
                ['label' => 'Order Fulfillment', 'target' => 'order-fulfillment'],
                ['label' => 'Sample Workflow', 'target' => 'sample-workflow'],
                ['label' => 'Order Reports', 'target' => 'reports'],
            ],
            'compliance' => [
                'title' => 'Nepal Order Compliance',
                'items' => [
                    'Capture customer PAN/VAT before issuing tax invoices.',
                    'Keep invoice numbering and order references consistent.',
                    'Support local delivery addresses using district, municipality, and ward.',
                    'Separate domestic and export order handling in reports.',
                ],
            ],
            'stats' => [
                ['label' => 'Open Orders', 'value' => DashboardRecord::query()->where('section', 'sales-orders')->count()],
                ['label' => 'Samples', 'value' => DashboardRecord::query()->where('section', 'samples')->count()],
                ['label' => 'Domestic Orders', 'value' => 'NPR market'],
                ['label' => 'Export Orders', 'value' => 'Cross-border'],
            ],
            'sections' => [
                [
                    'title' => 'Sales Order Flow',
                    'items' => [
                        'Customer inquiry and quotation',
                        'Sales order creation',
                        'Advance or credit confirmation',
                        'Packing, dispatch, and delivery',
                    ],
                ],
                [
                    'title' => 'Sample Workflow',
                    'items' => [
                        'Sample request entry',
                        'Fit and size approval',
                        'Feedback and revision',
                        'Final sample closure',
                    ],
                ],
                [
                    'title' => 'Nepal Order Context',
                    'items' => [
                        'Use NPR prices for local sales',
                        'Allow PAN/VAT customer identification',
                        'Support Kathmandu and district delivery fields',
                        'Track domestic and export order types',
                    ],
                ],
                [
                    'title' => 'Operational Reporting',
                    'items' => [
                        'Open order summary',
                        'Sample status report',
                        'Delivery performance',
                        'Sales by customer and region',
                    ],
                ],
            ],
        ];
    }

    private function financePages(): array
    {
        return [
            'income-management' => [
                'title' => 'Income Management',
                'subtitle' => 'Capture income, issue invoices, receive payments, and update balances.',
                'banner' => 'A disciplined income workflow ensures every sale or service receipt is recorded and reflected in customer balances.',
                'focus' => [
                    'Record sales or service income',
                    'Issue invoices to customers',
                    'Receive payment by cash, bank transfer, or digital payment',
                    'Update customer balances',
                ],
                'actions' => [
                    'Back to Finance Overview' => route('dashboard.section', 'finance'),
                    'Open Invoices' => route('dashboard.section', 'invoices'),
                ],
            ],
            'expense-management' => [
                'title' => 'Expense Management',
                'subtitle' => 'Record and monitor the organization daily operating expenses.',
                'banner' => 'Daily expense capture keeps management aware of spending and protects cash discipline.',
                'focus' => [
                    'Office rent',
                    'Electricity and internet',
                    'Fuel',
                    'Travel',
                    'Office supplies',
                    'Marketing',
                    'Miscellaneous expenses',
                ],
                'actions' => [
                    'Back to Finance Overview' => route('dashboard.section', 'finance'),
                    'Open Expenses' => route('dashboard.section', 'expenses'),
                ],
            ],
            'purchase-payments' => [
                'title' => 'Purchase Payments',
                'subtitle' => 'Receive supplier invoices, verify details, and pay obligations on time.',
                'banner' => 'Supplier invoice processing reduces errors and keeps accounts payable accurate and current.',
                'focus' => [
                    'Receive supplier invoices',
                    'Verify purchase details',
                    'Pay suppliers',
                    'Update accounts payable',
                ],
                'actions' => [
                    'Back to Finance Overview' => route('dashboard.section', 'finance'),
                    'Open Procurement' => route('dashboard.section', 'procurement'),
                ],
            ],
            'payroll' => [
                'title' => 'Payroll',
                'subtitle' => 'Record salaries, calculate deductions, and process employee payments.',
                'banner' => 'Payroll in finance keeps salary obligations visible and easy to reconcile with HR records.',
                'focus' => [
                    'Record employee salaries',
                    'Calculate allowances and deductions',
                    'Process salary payments',
                    'Generate payslips',
                ],
                'actions' => [
                    'Back to Finance Overview' => route('dashboard.section', 'finance'),
                    'Open HR & Payroll' => route('dashboard.section', 'hr-payroll'),
                ],
            ],
            'cash-bank' => [
                'title' => 'Cash & Bank Management',
                'subtitle' => 'Track cash, bank deposits, withdrawals, and daily balances.',
                'banner' => 'Cash and bank visibility gives management a clear view of available liquidity every day.',
                'focus' => [
                    'Record cash received and paid',
                    'Track bank deposits and withdrawals',
                    'Monitor daily cash balance',
                ],
                'actions' => [
                    'Back to Finance Overview' => route('dashboard.section', 'finance'),
                    'Open Reports' => route('dashboard.finance.page', 'reports'),
                ],
            ],
            'receivables' => [
                'title' => 'Accounts Receivable',
                'subtitle' => 'Track customer invoices, due dates, overdue balances, and collections.',
                'banner' => 'Receivables tracking helps the team follow up on customer balances before overdue amounts build up.',
                'focus' => [
                    'Outstanding invoices',
                    'Due dates',
                    'Overdue payments',
                    'Customer balances',
                ],
                'actions' => [
                    'Back to Finance Overview' => route('dashboard.section', 'finance'),
                    'Open Income Management' => route('dashboard.finance.page', 'income-management'),
                ],
            ],
            'payables' => [
                'title' => 'Accounts Payable',
                'subtitle' => 'Track supplier obligations and outstanding purchase bills.',
                'banner' => 'Payables control supports cash planning and avoids missed supplier deadlines.',
                'focus' => [
                    'Purchase bills',
                    'Payment due dates',
                    'Outstanding balances',
                    'Supplier obligations',
                ],
                'actions' => [
                    'Back to Finance Overview' => route('dashboard.section', 'finance'),
                    'Open Purchase Payments' => route('dashboard.finance.page', 'purchase-payments'),
                ],
            ],
            'reconciliation' => [
                'title' => 'Bank Reconciliation',
                'subtitle' => 'Compare cash book and bank statement, then resolve differences.',
                'banner' => 'Reconciliation keeps the finance record accurate by matching internal books with bank activity.',
                'focus' => [
                    'Company cash book',
                    'Bank statement',
                    'Difference analysis',
                    'Resolution tracking',
                ],
                'actions' => [
                    'Back to Finance Overview' => route('dashboard.section', 'finance'),
                    'Open Cash & Bank' => route('dashboard.finance.page', 'cash-bank'),
                ],
            ],
            'reports' => [
                'title' => 'Financial Reporting',
                'subtitle' => 'Monthly statements and management reports for decision-making.',
                'banner' => 'Management gets a full financial view from operating performance through balance and cash position.',
                'focus' => [
                    'Profit & Loss Statement',
                    'Balance Sheet',
                    'Cash Flow Statement',
                    'Income & Expense Report',
                    'Outstanding Receivables Report',
                    'Outstanding Payables Report',
                ],
                'actions' => [
                    'Back to Finance Overview' => route('dashboard.section', 'finance'),
                    'Open Receivables' => route('dashboard.finance.page', 'receivables'),
                ],
            ],
        ];
    }

    private function validateRecord(Request $request): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'meta' => ['nullable', 'string', 'max:255'],
            'status' => ['nullable', 'string', 'max:120'],
            'value' => ['nullable', 'string', 'max:255'],
            'pan_vat' => ['nullable', 'string', 'max:120'],
            'province' => ['nullable', 'string', 'max:120'],
            'district' => ['nullable', 'string', 'max:120'],
            'municipality' => ['nullable', 'string', 'max:120'],
            'ward' => ['nullable', 'string', 'max:120'],
            'order_type' => ['nullable', 'string', 'max:120'],
            'invoice_no' => ['nullable', 'string', 'max:120'],
            'due_date' => ['nullable', 'date'],
            'currency' => ['nullable', 'string', 'max:10'],
            'payment_mode' => ['nullable', 'string', 'max:120'],
            'employee_id' => ['nullable', 'string', 'max:120'],
            'employment_type' => ['nullable', 'string', 'max:120'],
            'ssf_no' => ['nullable', 'string', 'max:120'],
            'notes' => ['nullable', 'string', 'max:2000'],
            'is_active' => ['nullable', 'boolean'],
        ]);
    }
}
