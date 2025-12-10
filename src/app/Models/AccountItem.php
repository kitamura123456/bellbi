<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountItem extends Model
{
    use HasFactory;

    // 科目タイプ定数
    const TYPE_REVENUE = 1; // 売上
    const TYPE_EXPENSE = 2; // 経費

    protected $fillable = [
        'company_id',
        'type',
        'name',
        'default_tax_rate',
        'delete_flg',
    ];

    protected $casts = [
        'company_id' => 'integer',
        'type' => 'integer',
        'default_tax_rate' => 'decimal:2',
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
     * この科目を使用している取引
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * 有効な科目のみ取得するスコープ
     */
    public function scopeActive($query)
    {
        return $query->where('delete_flg', 0);
    }

    /**
     * 売上科目のみ取得するスコープ
     */
    public function scopeRevenue($query)
    {
        return $query->where('type', self::TYPE_REVENUE);
    }

    /**
     * 経費科目のみ取得するスコープ
     */
    public function scopeExpense($query)
    {
        return $query->where('type', self::TYPE_EXPENSE);
    }

    /**
     * タイプ名を取得
     */
    public function getTypeNameAttribute()
    {
        return match($this->type) {
            self::TYPE_REVENUE => '売上',
            self::TYPE_EXPENSE => '経費',
            default => '不明',
        };
    }
}


