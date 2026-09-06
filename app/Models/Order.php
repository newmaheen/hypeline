<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Order extends Model
{
    protected $fillable = [
    'order_code',
    'user_id',
    'invoice_token',
    'customer_name',
    'customer_phone',
    'customer_email',
    'shipping_address',
    'total_amount',
    'status',
    'payment_method',
    'payment_status',
    'transaction_id',
    'payment_phone',
];


    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function items()
{
    return $this->hasMany(OrderItem::class);
}
}