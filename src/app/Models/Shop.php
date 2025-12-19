<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'store_id',
        'name',
        'status',
        'description',
        'delete_flg',
    ];

    protected $casts = [
        'company_id' => 'integer',
        'store_id' => 'integer',
        'status' => 'integer',
        'delete_flg' => 'integer',
    ];

    // ステータス定数
    public const STATUS_PREPARING = 0;  // 準備中
    public const STATUS_PUBLIC = 1;     // 公開
    public const STATUS_STOPPED = 9;    // 停止

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class)->where('delete_flg', 0);
    }

    public function orders()
    {
        return $this->hasMany(Order::class)->where('delete_flg', 0);
    }
}

