<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OfflineSale extends Model
{
    protected $fillable = [
        'sale_id',
        'customer_name',
        'customer_phone',
        'sale_date',
        'subtotal',
        'discount',
        'total',
        'payment_method',
        'payment_status',
        'payment_reference',
        'notes',
        'status',
        'created_by',
        'cancelled_by',
        'cancelled_at',
        'cancellation_reason',
    ];

    protected $casts = [
        'sale_date' => 'date',
        'cancelled_at' => 'datetime',
    ];

    public function items()
    {
        return $this->hasMany(OfflineSaleItem::class);
    }

    public function creator()
    {
        return $this->belongsTo(Admin::class, 'created_by');
    }

    public function canceller()
    {
        return $this->belongsTo(Admin::class, 'cancelled_by');
    }

    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }
}