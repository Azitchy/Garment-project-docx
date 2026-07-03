<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('dashboard_records', function (Blueprint $table): void {
            $table->boolean('prevent_negative_stock')->default(false)->after('alert_threshold');
            $table->unsignedInteger('suggested_reorder_quantity')->nullable()->after('prevent_negative_stock');
            $table->string('purchase_invoice_no')->nullable()->after('purchase_order_no');
            $table->unsignedInteger('return_quantity')->nullable()->after('damaged_quantity');
            $table->decimal('refund_amount', 10, 2)->nullable()->after('return_quantity');
        });
    }

    public function down(): void
    {
        Schema::table('dashboard_records', function (Blueprint $table): void {
            $table->dropColumn([
                'prevent_negative_stock',
                'suggested_reorder_quantity',
                'purchase_invoice_no',
                'return_quantity',
                'refund_amount',
            ]);
        });
    }
};
