<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OfflineSaleItem extends Model
{
    protected $fillable = [
        'offline_sale_id',
        'product_id',
        'product_variant_id',
        'product_name',
        'variant_name',
        'quantity',
        'unit_price',
        'subtotal',
    ];

    public function offlineSale()
    {
        return $this->belongsTo(OfflineSale::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }
}