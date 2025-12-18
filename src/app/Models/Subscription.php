<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'plan_id',
        'status',
        'started_at',
        'ended_at',
        'next_billing_at',
        'delete_flg',
    ];

    protected $casts = [
        'company_id' => 'integer',
        'plan_id' => 'integer',
        'status' => 'integer',
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
        'next_billing_at' => 'datetime',
        'delete_flg' => 'integer',
    ];

    // ステータス定数
    public const STATUS_ACTIVE = 1;      // 有効
    public const STATUS_TRIAL = 2;        // トライアル
    public const STATUS_PAYMENT_DELAYED = 3; // 支払遅延
    public const STATUS_CANCELLED = 9;    // 解約

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    /**
     * 有効な契約のみを取得
     */
    public function scopeActive($query)
    {
        return $query->whereIn('status', [self::STATUS_ACTIVE, self::STATUS_TRIAL])
            ->where('delete_flg', 0);
    }

    /**
     * 現在有効な契約かチェック
     */
    public function isActive()
    {
        if ($this->delete_flg === 1) {
            return false;
        }

        if (!in_array($this->status, [self::STATUS_ACTIVE, self::STATUS_TRIAL])) {
            return false;
        }

        $now = Carbon::now();
        if ($this->started_at && $this->started_at->isFuture()) {
            return false;
        }

        if ($this->ended_at && $this->ended_at->isPast()) {
            return false;
        }

        return true;
    }
}

