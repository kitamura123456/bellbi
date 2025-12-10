<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    // 取引タイプ定数
    const TYPE_REVENUE = 1; // 売上
    const TYPE_EXPENSE = 2; // 経費

    // 取引ソース定数
    const SOURCE_MANUAL = 1;     // 手入力
    const SOURCE_EC = 2;         // EC連携
    const SOURCE_EXTERNAL = 3;   // 外部API

    protected $fillable = [
        'company_id',
        'store_id',
        'date',
        'account_item_id',
        'amount',
        'tax_amount',
        'transaction_type',
        'source_type',
        'note',
        'delete_flg',
    ];

    protected $casts = [
        'company_id' => 'integer',
        'store_id' => 'integer',
        'date' => 'date',
        'account_item_id' => 'integer',
        'amount' => 'integer',
        'tax_amount' => 'integer',
        'transaction_type' => 'integer',
        'source_type' => 'integer',
        'delete_flg' => 'integer',
    ];

    /**
     * 所属する会社
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * 所属する店舗
     */
    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    /**
     * 使用している科目
     */
    public function accountItem()
    {
        return $this->belongsTo(AccountItem::class);
    }

    /**
     * 有効な取引のみ取得するスコープ
     */
    public function scopeActive($query)
    {
        return $query->where('delete_flg', 0);
    }

    /**
     * 売上取引のみ取得するスコープ
     */
    public function scopeRevenue($query)
    {
        return $query->where('transaction_type', self::TYPE_REVENUE);
    }

    /**
     * 経費取引のみ取得するスコープ
     */
    public function scopeExpense($query)
    {
        return $query->where('transaction_type', self::TYPE_EXPENSE);
    }

    /**
     * 期間で絞り込むスコープ
     */
    public function scopeBetweenDates($query, $startDate, $endDate)
    {
        return $query->whereBetween('date', [$startDate, $endDate]);
    }

    /**
     * 取引タイプ名を取得
     */
    public function getTypeNameAttribute()
    {
        return match($this->transaction_type) {
            self::TYPE_REVENUE => '売上',
            self::TYPE_EXPENSE => '経費',
            default => '不明',
        };
    }

    /**
     * ソースタイプ名を取得
     */
    public function getSourceNameAttribute()
    {
        return match($this->source_type) {
            self::SOURCE_MANUAL => '手入力',
            self::SOURCE_EC => 'EC連携',
            self::SOURCE_EXTERNAL => '外部API',
            default => '不明',
        };
    }

    /**
     * 税込金額を取得
     */
    public function getTotalAmountAttribute()
    {
        return $this->amount + $this->tax_amount;
    }
}


