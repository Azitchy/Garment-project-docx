<?php

namespace App\Http\Controllers;

use App\Models\DashboardRecord;
use App\Models\Garment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminSectionController extends Controller
{
    public function show(string $section): View
    {
        $sections = self::sections();
        abort_unless(array_key_exists($section, $sections), 404);

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

        if ($section === 'inventory') {
            $viewData['inventoryOverview'] = $this->inventoryOverview();
        }

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

        return view('admin.inventory-page', [
            'sidebarSection' => 'inventory',
            'sidebarSubsection' => $page,
            'sectionKey' => 'inventory',
            'sectionMeta' => self::sections()['inventory'],
            'pageKey' => $page,
            'pageMeta' => $pages[$page],
            'highlights' => $this->buildHighlights('inventory'),
            'inventoryOverview' => $this->inventoryOverview(),
            'records' => DashboardRecord::query()
                ->where('section', 'inventory')
                ->latest()
                ->paginate(10)
                ->withQueryString(),
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
                    'Open Inventory Sales Process' => route('dashboard.inventory.page', 'sales-process'),
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
            'inventory' => ['title' => 'Inventory', 'subtitle' => 'Sales flow, stock control, reorder alerts, and reporting.'],
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
                    'sales-process' => ['label' => 'Sales Process', 'route' => ['dashboard.inventory.page', ['sales-process']]],
                    'monitoring' => ['label' => 'Monitoring', 'route' => ['dashboard.inventory.page', ['monitoring']]],
                    'reorder' => ['label' => 'Reorder', 'route' => ['dashboard.inventory.page', ['reorder']]],
                    'reports' => ['label' => 'Reports', 'route' => ['dashboard.inventory.page', ['reports']]],
                    'stock-items' => ['label' => 'Stock Items', 'route' => ['dashboard.section', ['stock-items']]],
                    'stock-movements' => ['label' => 'Stock Movements', 'route' => ['dashboard.section', ['stock-movements']]],
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
                ['label' => 'Current Stock', 'value' => Garment::query()->sum('stock'), 'tone' => 'blue', 'icon' => 'CS'],
                ['label' => 'Low Stock SKUs', 'value' => Garment::query()->where('stock', '<=', 5)->count(), 'tone' => 'amber', 'icon' => 'LS'],
                ['label' => 'Inventory Value', 'value' => '$' . number_format(Garment::query()->get()->sum(fn (Garment $garment): float => (float) $garment->price * (int) $garment->stock), 2), 'tone' => 'green', 'icon' => 'IV'],
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
        $value = Garment::query()->get()->sum(fn (Garment $garment): float => (float) $garment->price * (int) $garment->stock);

        return [
            'flow' => [
                'title' => 'Sales Process',
                'subtitle' => 'Customer Order -> Sales Order -> Picking -> Packing -> Delivery -> Invoice -> Stock Reduced',
                'steps' => [
                    ['label' => 'Customer Order', 'meta' => 'Captured from sales team or buyer portal.'],
                    ['label' => 'Sales Order', 'meta' => 'Converted into a fulfillable demand record.'],
                    ['label' => 'Picking', 'meta' => 'Warehouse reserves stock and prepares items.'],
                    ['label' => 'Packing', 'meta' => 'Items are packed for shipment.'],
                    ['label' => 'Delivery', 'meta' => 'Goods leave the warehouse.'],
                    ['label' => 'Invoice', 'meta' => 'Billing is issued and tracked.'],
                    ['label' => 'Stock Reduced', 'meta' => 'Inventory balances update automatically.'],
                ],
            ],
            'monitoring' => [
                'title' => 'Inventory Monitoring',
                'items' => [
                    'Current stock',
                    'Reserved stock',
                    'Available stock',
                    'Damaged stock',
                    'Expired stock',
                    'Returned items',
                ],
            ],
            'reorder' => [
                'title' => 'Reorder Management',
                'items' => [
                    'Low stock notification',
                    'Purchase request generated',
                    'New purchase order created',
                ],
            ],
            'reports' => [
                'title' => 'Reporting',
                'items' => [
                    'Stock Summary',
                    'Stock Movement',
                    'Purchase Report',
                    'Sales Report',
                    'Warehouse Report',
                    'Expiry Report',
                    'Inventory Valuation',
                    'Fast-moving items',
                    'Slow-moving items',
                ],
            ],
            'quickActions' => [
                ['label' => 'Sales Process', 'target' => 'sales-process'],
                ['label' => 'Inventory Monitoring', 'target' => 'monitoring'],
                ['label' => 'Reorder Management', 'target' => 'reorder'],
                ['label' => 'Reporting', 'target' => 'reports'],
            ],
            'stats' => [
                ['label' => 'Current Stock', 'value' => Garment::query()->sum('stock')],
                ['label' => 'Reserved Stock', 'value' => max(0, Garment::query()->sum('stock') - Garment::query()->where('stock', '<=', 5)->sum('stock'))],
                ['label' => 'Available Stock', 'value' => Garment::query()->sum('stock') - 2],
                ['label' => 'Damaged Stock', 'value' => 0],
                ['label' => 'Expired Stock', 'value' => 0],
                ['label' => 'Returned Items', 'value' => 0],
                ['label' => 'Inventory Valuation', 'value' => '$' . number_format($value, 2)],
            ],
        ];
    }

    private function inventoryPages(): array
    {
        return [
            'sales-process' => [
                'title' => 'Sales Process',
                'subtitle' => 'Order capture through billing and stock reduction.',
                'banner' => 'A controlled sales flow keeps finance, warehouse, and dispatch aligned from the first customer order.',
                'focus' => [
                    'Customer Order intake',
                    'Sales Order confirmation',
                    'Picking and packing handoff',
                    'Delivery and invoice closure',
                ],
                'actions' => [
                    'Back to Inventory Overview' => route('dashboard.section', 'inventory'),
                    'Open Sales Orders' => route('dashboard.section', 'sales-orders'),
                ],
            ],
            'monitoring' => [
                'title' => 'Inventory Monitoring',
                'subtitle' => 'Continuously track stock states across the warehouse.',
                'banner' => 'Live inventory visibility helps the team react before stockouts, damages, or expiry issues hit operations.',
                'focus' => [
                    'Current stock',
                    'Reserved stock',
                    'Available stock',
                    'Damaged stock',
                    'Expired stock',
                    'Returned items',
                ],
                'actions' => [
                    'Back to Inventory Overview' => route('dashboard.section', 'inventory'),
                    'Open Stock Movements' => route('dashboard.section', 'stock-movements'),
                ],
            ],
            'reorder' => [
                'title' => 'Reorder Management',
                'subtitle' => 'Automate low-stock response and purchasing follow-up.',
                'banner' => 'Minimum-level monitoring can trigger the next procurement action without waiting for manual escalation.',
                'focus' => [
                    'Low stock notification',
                    'Purchase request generation',
                    'Purchase order creation',
                ],
                'actions' => [
                    'Back to Inventory Overview' => route('dashboard.section', 'inventory'),
                    'Open Procurement' => route('dashboard.section', 'procurement'),
                ],
            ],
            'reports' => [
                'title' => 'Reporting',
                'subtitle' => 'Management reporting across stock, purchasing, sales, and warehouse activity.',
                'banner' => 'The reporting layer gives decision-makers a single place to review performance, value, and movement trends.',
                'focus' => [
                    'Stock Summary',
                    'Stock Movement',
                    'Purchase Report',
                    'Sales Report',
                    'Warehouse Report',
                    'Expiry Report',
                    'Inventory Valuation',
                    'Fast-moving items',
                    'Slow-moving items',
                ],
                'actions' => [
                    'Back to Inventory Overview' => route('dashboard.section', 'inventory'),
                    'Open Reports Section' => route('dashboard.section', 'reports'),
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
