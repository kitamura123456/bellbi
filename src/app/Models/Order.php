<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'shop_id',
        'user_id',
        'total_amount',
        'status',
        'stripe_payment_intent_id',
        'stripe_checkout_session_id',
        'delete_flg',
    ];

    protected $casts = [
        'shop_id' => 'integer',
        'user_id' => 'integer',
        'total_amount' => 'integer',
        'status' => 'integer',
        'delete_flg' => 'integer',
    ];

    // ステータス定数
    public const STATUS_NEW = 1;           // 新規
    public const STATUS_PAID = 2;          // 入金確認済
    public const STATUS_SHIPPED = 3;       // 発送済
    public const STATUS_COMPLETED = 4;     // 完了
    public const STATUS_CANCELLED = 9;     // キャンセル

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class)->where('delete_flg', 0);
    }
}

