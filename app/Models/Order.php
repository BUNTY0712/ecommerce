<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'order_number',
        'customer_name',
        'email',
        'phone',
        'address',
        'address_line_2',
        'city',
        'state',
        'pincode',
        'country',
        'subtotal',
        'shipping_charge',
        'total_amount',
        'payment_method',
        'payment_status',
        'order_status',
        'razorpay_order_id',
        'razorpay_payment_id',
    ];
}
