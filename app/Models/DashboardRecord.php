<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DashboardRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'section',
        'title',
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
        ];
    }
}
