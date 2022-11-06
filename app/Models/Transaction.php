<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_name',
        'user_phone',
        'address',
        'amount',
        'payment',
        'payment_info',
        'security',
        'status',
    ];

    public function order()
    {
        return $this->hasMany(Order::class, 'transaction_id', 'id');
    }
}
