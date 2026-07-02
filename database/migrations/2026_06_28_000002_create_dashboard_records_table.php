<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dashboard_records', function (Blueprint $table): void {
            $table->id();
            $table->string('section', 80);
            $table->string('title');
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
            $table->string('ssf_no')->nullable();
            $table->text('notes')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['section', 'title']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dashboard_records');
    }
};
