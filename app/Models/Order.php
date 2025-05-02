<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'product_id',
        'name',
        'email',
        'product_name',
        'description',
        'quantity',
        'total_price',
        'order_date',
        'status',
    ];
}
