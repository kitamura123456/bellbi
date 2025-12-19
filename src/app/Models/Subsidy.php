<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Subsidy extends Model
{
    use HasFactory;

    // ステータス定数
    const STATUS_RECRUITING = 1; // 募集中
    const STATUS_CLOSED = 2;     // 締切
    const STATUS_NOT_STARTED = 3; // 未開始

    // カテゴリ定数
    const CATEGORY_EQUIPMENT = 1;      // 設備投資
    const CATEGORY_HUMAN_RESOURCES = 2; // 人材確保
    const CATEGORY_BUSINESS_CONTINUITY = 3; // 事業継続
    const CATEGORY_OTHER = 4;          // その他

    protected $fillable = [
        'title',
        'category',
        'target_region',
        'applicable_industry_type',
        'application_start_at',
        'application_end_at',
        'status',
        'summary',
        'detail_url',
        'delete_flg',
    ];

    protected $casts = [
        'category' => 'integer',
        'target_region' => 'integer',
        'applicable_industry_type' => 'integer',
        'application_start_at' => 'datetime',
        'application_end_at' => 'datetime',
        'status' => 'integer',
        'delete_flg' => 'integer',
    ];

    /**
     * 有効な補助金のみ取得するスコープ
     */
    public function scopeActive($query)
    {
        return $query->where('delete_flg', 0);
    }

    /**
     * 募集中の補助金のみ取得するスコープ
     */
    public function scopeRecruiting($query)
    {
        return $query->where('status', self::STATUS_RECRUITING);
    }

    /**
     * ステータス名を取得
     */
    public function getStatusNameAttribute()
    {
        return match($this->status) {
            self::STATUS_RECRUITING => '募集中',
            self::STATUS_CLOSED => '締切',
            self::STATUS_NOT_STARTED => '未開始',
            default => '不明',
        };
    }

    /**
     * カテゴリ名を取得
     */
    public function getCategoryNameAttribute()
    {
        return match($this->category) {
            self::CATEGORY_EQUIPMENT => '設備投資',
            self::CATEGORY_HUMAN_RESOURCES => '人材確保',
            self::CATEGORY_BUSINESS_CONTINUITY => '事業継続',
            self::CATEGORY_OTHER => 'その他',
            default => '未設定',
        };
    }

    /**
     * 申請期間内かどうかを判定
     */
    public function isApplicationPeriod(): bool
    {
        $now = Carbon::now();
        
        if ($this->application_start_at && $now->lt($this->application_start_at)) {
            return false;
        }
        
        if ($this->application_end_at && $now->gt($this->application_end_at)) {
            return false;
        }
        
        return true;
    }
}
