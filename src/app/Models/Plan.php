<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price_monthly',
        'features_bitmask',
        'status',
        'delete_flg',
    ];

    protected $casts = [
        'price_monthly' => 'integer',
        'features_bitmask' => 'integer',
        'status' => 'integer',
        'delete_flg' => 'integer',
    ];

    // ステータス定数
    public const STATUS_ACTIVE = 1;  // 有効
    public const STATUS_INACTIVE = 0; // 無効

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    /**
     * 有効なプランのみを取得
     */
    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE)
            ->where('delete_flg', 0);
    }

    /**
     * 機能が有効かチェック（ビットマスク）
     */
    public function hasFeature($featureBit)
    {
        return ($this->features_bitmask & $featureBit) !== 0;
    }
}

