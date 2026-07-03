<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DashboardRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'section',
        'record_type',
        'title',
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
        'purchase_invoice_no',
        'sale_invoice_no',
        'transfer_status',
        'alert_threshold',
        'prevent_negative_stock',
        'suggested_reorder_quantity',
        'adjustment_reason',
        'return_quantity',
        'refund_amount',
        'transaction_date',
        'meta',
        'status',
        'value',
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
        'notes',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'due_date' => 'date',
            'transaction_date' => 'date',
            'cost_price' => 'decimal:2',
            'selling_price' => 'decimal:2',
            'quantity' => 'integer',
            'reserved_quantity' => 'integer',
            'damaged_quantity' => 'integer',
            'alert_threshold' => 'integer',
            'prevent_negative_stock' => 'boolean',
            'suggested_reorder_quantity' => 'integer',
            'return_quantity' => 'integer',
            'refund_amount' => 'decimal:2',
        ];
    }
}
