<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScoutMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'from_company_id',
        'from_store_id',
        'to_user_id',
        'scout_profile_id',
        'status',
        'subject',
        'body',
        'delete_flg',
    ];

    protected $casts = [
        'from_company_id' => 'integer',
        'from_store_id' => 'integer',
        'to_user_id' => 'integer',
        'scout_profile_id' => 'integer',
        'status' => 'integer',
        'delete_flg' => 'integer',
    ];

    // ステータス定義
    public const STATUS_SENT = 1;      // 送信済
    public const STATUS_READ = 2;      // 既読
    public const STATUS_REPLIED = 3;   // 返信あり
    public const STATUS_CLOSED = 9;    // クローズ

    public function fromCompany()
    {
        return $this->belongsTo(Company::class, 'from_company_id');
    }

    public function fromStore()
    {
        return $this->belongsTo(Store::class, 'from_store_id');
    }

    public function toUser()
    {
        return $this->belongsTo(User::class, 'to_user_id');
    }

    public function scoutProfile()
    {
        return $this->belongsTo(ScoutProfile::class);
    }
}


