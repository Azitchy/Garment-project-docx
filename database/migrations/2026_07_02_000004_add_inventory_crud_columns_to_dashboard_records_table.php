<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('dashboard_records', function (Blueprint $table): void {
            $table->string('record_type', 80)->nullable()->after('section')->index();
            $table->string('sku')->nullable()->after('title')->unique();
            $table->string('barcode')->nullable()->after('sku')->unique();
            $table->string('category')->nullable()->after('barcode');
            $table->string('supplier')->nullable()->after('category');
            $table->string('customer')->nullable()->after('supplier');
            $table->string('contact_name')->nullable()->after('customer');
            $table->string('contact_phone')->nullable()->after('contact_name');
            $table->string('contact_email')->nullable()->after('contact_phone');
            $table->string('unit')->nullable()->after('contact_email');
            $table->decimal('cost_price', 10, 2)->nullable()->after('unit');
            $table->decimal('selling_price', 10, 2)->nullable()->after('cost_price');
            $table->unsignedInteger('quantity')->nullable()->after('selling_price');
            $table->unsignedInteger('reserved_quantity')->nullable()->after('quantity');
            $table->unsignedInteger('damaged_quantity')->nullable()->after('reserved_quantity');
            $table->string('warehouse')->nullable()->after('damaged_quantity');
            $table->string('source_warehouse')->nullable()->after('warehouse');
            $table->string('destination_warehouse')->nullable()->after('source_warehouse');
            $table->string('reference_no')->nullable()->after('destination_warehouse');
            $table->string('purchase_order_no')->nullable()->after('reference_no');
            $table->string('sale_invoice_no')->nullable()->after('purchase_order_no');
            $table->string('transfer_status')->nullable()->after('sale_invoice_no');
            $table->unsignedInteger('alert_threshold')->nullable()->after('transfer_status');
            $table->string('adjustment_reason')->nullable()->after('alert_threshold');
            $table->date('transaction_date')->nullable()->after('adjustment_reason');
        });
    }

    public function down(): void
    {
        Schema::table('dashboard_records', function (Blueprint $table): void {
            $table->dropColumn([
                'record_type',
                'sku',
                'barcode',
                'category',
                'supplier',
                'customer',
                'contact_name',
                'contact_phone',
                'contact_email',
                'unit',
                'cost_price',
                'selling_price',
                'quantity',
                'reserved_quantity',
                'damaged_quantity',
                'warehouse',
                'source_warehouse',
                'destination_warehouse',
                'reference_no',
                'purchase_order_no',
                'sale_invoice_no',
                'transfer_status',
                'alert_threshold',
                'adjustment_reason',
                'transaction_date',
            ]);
        });
    }
};
