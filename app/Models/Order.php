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
        'product_name',
        'quantity',
        'price',
        'data',
        'status',
        'transaction_id',
    ];

    public function product() 
    {
        return $this->hasOne(Product::class, 'id', 'product_id');
    }
}
