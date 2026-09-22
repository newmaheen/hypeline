<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Category;
use App\Models\ProductVariant;
use App\Models\OrderItem;
use App\Models\OfflineSaleItem;

class Product extends Model
{
    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'price',
        'sale_price',
        'description',
        'image',       // ager single image thakle compatibility-r jonno thakuk
        'images',      // multiple images er array/json column
        'is_active',
    ];

    // JSON column-ke auto PHP array te convert korar jonno
    protected $casts = [
        'images' => 'array',
        'is_active' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    // Order history ache kina check korar relation
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    // POS / Offline sale history check korar relation
    public function offlineSaleItems()
    {
        return $this->hasMany(OfflineSaleItem::class);
    }
}