<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('dashboard_records', function (Blueprint $table): void {
            if (! Schema::hasColumn('dashboard_records', 'pan_vat')) {
                $table->string('pan_vat')->nullable()->after('value');
            }

            if (! Schema::hasColumn('dashboard_records', 'province')) {
                $table->string('province')->nullable()->after('pan_vat');
            }

            if (! Schema::hasColumn('dashboard_records', 'district')) {
                $table->string('district')->nullable()->after('province');
            }

            if (! Schema::hasColumn('dashboard_records', 'municipality')) {
                $table->string('municipality')->nullable()->after('district');
            }

            if (! Schema::hasColumn('dashboard_records', 'ward')) {
                $table->string('ward')->nullable()->after('municipality');
            }

            if (! Schema::hasColumn('dashboard_records', 'order_type')) {
                $table->string('order_type')->nullable()->after('ward');
            }

            if (! Schema::hasColumn('dashboard_records', 'invoice_no')) {
                $table->string('invoice_no')->nullable()->after('order_type');
            }

            if (! Schema::hasColumn('dashboard_records', 'due_date')) {
                $table->date('due_date')->nullable()->after('invoice_no');
            }

            if (! Schema::hasColumn('dashboard_records', 'currency')) {
                $table->string('currency', 10)->nullable()->after('due_date');
            }

            if (! Schema::hasColumn('dashboard_records', 'payment_mode')) {
                $table->string('payment_mode')->nullable()->after('currency');
            }

            if (! Schema::hasColumn('dashboard_records', 'employee_id')) {
                $table->string('employee_id')->nullable()->after('payment_mode');
            }

            if (! Schema::hasColumn('dashboard_records', 'employment_type')) {
                $table->string('employment_type')->nullable()->after('employee_id');
            }

            if (! Schema::hasColumn('dashboard_records', 'ssf_no')) {
                $table->string('ssf_no')->nullable()->after('employment_type');
            }
        });
    }

    public function down(): void
    {
        Schema::table('dashboard_records', function (Blueprint $table): void {
            $columns = [
                'pan_vat',
                'province',
                'district',
                'municipality',
                'ward',
                'order_type',
                'invoice_no',
                'due_date',
                'currency',
                'payment_mode',
                'employee_id',
                'employment_type',
                'ssf_no',
            ];

            $existing = array_filter($columns, fn (string $column): bool => Schema::hasColumn('dashboard_records', $column));

            if ($existing !== []) {
                $table->dropColumn($existing);
            }
        });
    }
};
