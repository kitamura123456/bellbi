<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'store_id',
        'user_id',
        'staff_id',
        'reservation_date',
        'start_time',
        'end_time',
        'total_duration_minutes',
        'total_price',
        'status',
        'customer_note',
        'store_note',
        'delete_flg',
    ];

    protected $casts = [
        'store_id' => 'integer',
        'user_id' => 'integer',
        'staff_id' => 'integer',
        'reservation_date' => 'date',
        'total_duration_minutes' => 'integer',
        'total_price' => 'integer',
        'status' => 'integer',
        'delete_flg' => 'integer',
    ];

    // ステータス定数
    public const STATUS_CONFIRMED = 1;      // 予約確定
    public const STATUS_VISITED = 2;        // 来店済
    public const STATUS_STORE_CANCELLED = 3; // 店舗キャンセル
    public const STATUS_USER_CANCELLED = 4;  // 顧客キャンセル
    public const STATUS_NO_SHOW = 9;        // 無断キャンセル

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function staff()
    {
        return $this->belongsTo(StoreStaff::class, 'staff_id');
    }

    public function reservationMenus()
    {
        return $this->hasMany(ReservationMenu::class);
    }
}

