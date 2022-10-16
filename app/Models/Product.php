<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'image_product',
        'discount',
        'content',
        'price',
        'view',
    ];

    public function category()
    {
        return $this->hasOne(Category::class, 'id', 'category_id');
    }

    public function imagePR()
    {
        return $this->hasMany(ProductImage::class, 'product_id', 'id');
    }
}
