<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'shop_id',
        'name',
        'description',
        'price',
        'stock',
        'category',
        'status',
        'delete_flg',
    ];

    protected $casts = [
        'shop_id' => 'integer',
        'price' => 'integer',
        'stock' => 'integer',
        'category' => 'integer',
        'status' => 'integer',
        'delete_flg' => 'integer',
    ];

    // ステータス定数
    public const STATUS_ON_SALE = 1;      // 販売中
    public const STATUS_OUT_OF_STOCK = 2; // 在庫切れ
    public const STATUS_PRIVATE = 9;      // 非公開

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}

