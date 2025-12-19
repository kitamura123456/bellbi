<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'contact_name',
        'industry_type',
        'business_category',
        'postal_code',
        'address',
        'tel',
        'plan_id',
        'plan_status',
        'delete_flg',
    ];

    protected $casts = [
        'industry_type' => 'integer',
        'business_category' => 'integer',
        'plan_id' => 'integer',
        'plan_status' => 'integer',
        'delete_flg' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function stores()
    {
        return $this->hasMany(Store::class);
    }

    public function shops()
    {
        return $this->hasMany(Shop::class)->where('delete_flg', 0);
    }

    public function jobPosts()
    {
        return $this->hasMany(JobPost::class);
    }

    public function accountItems()
    {
        return $this->hasMany(AccountItem::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function conversations()
    {
        return $this->hasMany(Conversation::class);
    }

    public function images()
    {
        return $this->hasMany(CompanyImage::class)->where('delete_flg', 0)->orderBy('sort_order');
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    /**
     * 現在有効な契約を取得
     */
    public function activeSubscription()
    {
        return $this->subscriptions()
            ->whereIn('status', [Subscription::STATUS_ACTIVE, Subscription::STATUS_TRIAL])
            ->where('delete_flg', 0)
            ->where(function($query) {
                $query->whereNull('started_at')
                    ->orWhere('started_at', '<=', now());
            })
            ->where(function($query) {
                $query->whereNull('ended_at')
                    ->orWhere('ended_at', '>', now());
            })
            ->orderBy('created_at', 'desc')
            ->first();
    }
}


