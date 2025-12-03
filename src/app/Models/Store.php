<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'name',
        'store_type',
        'postal_code',
        'address',
        'tel',
        'delete_flg',
    ];

    protected $casts = [
        'company_id' => 'integer',
        'store_type' => 'integer',
        'delete_flg' => 'integer',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function jobPosts()
    {
        return $this->hasMany(JobPost::class);
    }
}


