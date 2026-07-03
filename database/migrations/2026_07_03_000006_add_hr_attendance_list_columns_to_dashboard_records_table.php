<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('DROP TABLE IF EXISTS `dashboard_records`');

        Schema::create('dashboard_records', function (Blueprint $table): void {
            $table->id();
            $table->string('section', 80);
            $table->string('record_type', 80)->nullable()->index();
            $table->string('title');
            $table->string('sku')->nullable()->unique();
            $table->string('barcode')->nullable()->unique();
            $table->string('category')->nullable();
            $table->string('supplier')->nullable();
            $table->string('customer')->nullable();
            $table->string('contact_name')->nullable();
            $table->string('contact_phone')->nullable();
            $table->string('contact_email')->nullable();
            $table->string('unit')->nullable();
            $table->decimal('cost_price', 10, 2)->nullable();
            $table->decimal('selling_price', 10, 2)->nullable();
            $table->unsignedInteger('quantity')->nullable();
            $table->unsignedInteger('reserved_quantity')->nullable();
            $table->unsignedInteger('damaged_quantity')->nullable();
            $table->string('warehouse')->nullable();
            $table->string('source_warehouse')->nullable();
            $table->string('destination_warehouse')->nullable();
            $table->string('reference_no')->nullable();
            $table->string('purchase_order_no')->nullable();
            $table->string('purchase_invoice_no')->nullable();
            $table->string('sale_invoice_no')->nullable();
            $table->string('transfer_status')->nullable();
            $table->unsignedInteger('alert_threshold')->nullable();
            $table->boolean('prevent_negative_stock')->default(false);
            $table->unsignedInteger('suggested_reorder_quantity')->nullable();
            $table->string('adjustment_reason')->nullable();
            $table->unsignedInteger('return_quantity')->nullable();
            $table->decimal('refund_amount', 10, 2)->nullable();
            $table->date('transaction_date')->nullable();
            $table->string('meta')->nullable();
            $table->string('status')->nullable();
            $table->string('value')->nullable();
            $table->string('pan_vat')->nullable();
            $table->string('province')->nullable();
            $table->string('district')->nullable();
            $table->string('municipality')->nullable();
            $table->string('ward')->nullable();
            $table->string('order_type')->nullable();
            $table->string('invoice_no')->nullable();
            $table->date('due_date')->nullable();
            $table->string('currency', 10)->nullable();
            $table->string('payment_mode')->nullable();
            $table->string('employee_id')->nullable();
            $table->string('employment_type')->nullable();
            $table->text('present_employees')->nullable();
            $table->text('absent_employees')->nullable();
            $table->string('ssf_no')->nullable();
            $table->text('notes')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['section', 'title']);
        });
    }

    public function down(): void
    {
        if (Schema::hasTable('dashboard_records')) {
            Schema::table('dashboard_records', function (Blueprint $table): void {
                $columns = array_filter([
                    'present_employees',
                    'absent_employees',
                ], fn (string $column): bool => Schema::hasColumn('dashboard_records', $column));

                if ($columns !== []) {
                    $table->dropColumn($columns);
                }
            });
        }
    }
};
