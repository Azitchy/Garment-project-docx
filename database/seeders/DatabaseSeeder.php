<?php

namespace Database\Seeders;

use App\Models\DashboardRecord;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(GarmentSeeder::class);

        $now = now();

        DashboardRecord::query()->truncate();

        DashboardRecord::insert([
            ['section' => 'products', 'title' => 'Classic Cotton Shirt', 'meta' => 'Shirts', 'status' => 'Live', 'value' => 'Stock 24', 'notes' => 'Core product master.', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['section' => 'customers', 'title' => 'Apex Retail', 'meta' => 'Wholesale', 'status' => 'Active', 'value' => '12 orders', 'notes' => 'Key account customer.', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['section' => 'vendors', 'title' => 'Alpha Textiles', 'meta' => 'Fabric', 'status' => 'Preferred', 'value' => '7 day lead', 'notes' => 'Certified fabric vendor.', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['section' => 'sales-orders', 'title' => 'SO-1042', 'meta' => 'Metro Fashions', 'status' => 'In Production', 'value' => 'Rs. 24,900', 'notes' => 'Current production order.', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['section' => 'samples', 'title' => 'Sample A-01', 'meta' => 'Shirt', 'status' => 'Review', 'value' => 'Fit check', 'notes' => 'PP sample pending approval.', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['section' => 'stock-items', 'title' => 'Cotton Roll', 'meta' => 'Fabric', 'status' => 'OK', 'value' => '120 m', 'notes' => 'Main stock item.', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['section' => 'stock-movements', 'title' => 'Receive Cotton Roll', 'meta' => 'Inbound', 'status' => 'Posted', 'value' => '08:15', 'notes' => 'GRN posted today.', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['section' => 'production-lines', 'title' => 'Line A', 'meta' => 'Shirts', 'status' => 'Running', 'value' => '82%', 'notes' => 'Primary line.', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['section' => 'daily-production', 'title' => 'Morning Shift', 'meta' => '148 pcs', 'status' => 'On Track', 'value' => '48%', 'notes' => 'Shift production log.', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['section' => 'cutting', 'title' => 'Cut Job 21', 'meta' => 'Shirt panel', 'status' => 'Done', 'value' => '120 sets', 'notes' => 'Cutting job record.', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['section' => 'quality-control', 'title' => 'Batch QC-01', 'meta' => 'Shirts', 'status' => 'Passed', 'value' => '2 defects', 'notes' => 'Inline QC checkpoint.', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['section' => 'employees', 'title' => 'Sita Rai', 'meta' => 'Production', 'status' => 'Active', 'value' => 'Supervisor', 'notes' => 'Department lead.', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['section' => 'attendance', 'title' => 'Sita Rai', 'meta' => '08:02', 'status' => 'Present', 'value' => 'Shift A', 'notes' => 'Attendance check-in.', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['section' => 'payroll', 'title' => 'June Payroll', 'meta' => 'All staff', 'status' => 'Processing', 'value' => 'Rs. 325,000', 'notes' => 'Monthly payroll cycle.', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['section' => 'compliance', 'title' => 'Fire Drill', 'meta' => 'Safety', 'status' => 'Scheduled', 'value' => 'Next week', 'notes' => 'Safety drill record.', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['section' => 'procurement', 'title' => 'PR-2201', 'meta' => 'Fabric', 'status' => 'Approved', 'value' => 'Rs. 42,000', 'notes' => 'Approved requisition.', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['section' => 'invoices', 'title' => 'INV-0012', 'meta' => 'Metro Fashions', 'status' => 'Open', 'value' => 'Rs. 94,872', 'notes' => 'Pending billing.', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['section' => 'expenses', 'title' => 'Fabric purchase', 'meta' => 'Procurement', 'status' => 'Approved', 'value' => 'Rs. 42,000', 'notes' => 'Operational expense.', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['section' => 'shipments', 'title' => 'SHP-2001', 'meta' => 'Metro Fashions', 'status' => 'Packed', 'value' => 'Dispatch today', 'notes' => 'Ready for booking.', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['section' => 'reports', 'title' => 'Monthly Operations', 'meta' => 'PDF', 'status' => 'Ready', 'value' => 'Download', 'notes' => 'Standard report.', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['section' => 'settings', 'title' => 'Company Profile', 'meta' => 'Branding', 'status' => 'Configured', 'value' => 'OK', 'notes' => 'System settings.', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
        ]);

        User::updateOrCreate(
            ['email' => 'admin#gms.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'permissions' => [
                    'dashboard_access',
                    'master_data_access',
                    'orders_access',
                    'inventory_access',
                    'hr_payroll_access',
                    'finance_access',
                    'reports_access',
                    'user_management_access',
                    'inventory_edit',
                    'finance_edit',
                    'hr_edit',
                    'order_edit',
                ],
            ]
        );
    }
}
