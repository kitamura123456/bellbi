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

    public function jobPosts()
    {
        return $this->hasMany(JobPost::class);
    }
}


