<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'email_shop',
        'name_shop',
        'phone_number',
        'image_shop',
        'description',
        'address',
    ];

    public function owner()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function categories()
    {
        return $this->hasMany(Category::class, 'shop_id', 'id');
    }
}
