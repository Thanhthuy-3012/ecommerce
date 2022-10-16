<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'shop_id',
        'name_category',
    ];

    public function shop()
    {
        return $this->hasOne(Shop::class, 'id', 'shop_id');
    }
}
