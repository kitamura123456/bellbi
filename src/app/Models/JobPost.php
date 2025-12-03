<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobPost extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'store_id',
        'title',
        'description',
        'job_category',
        'employment_type',
        'prefecture_code',
        'city',
        'min_salary',
        'max_salary',
        'work_location',
        'status',
        'publish_start_at',
        'publish_end_at',
        'delete_flg',
    ];

    protected $casts = [
        'company_id' => 'integer',
        'store_id' => 'integer',
        'job_category' => 'integer',
        'employment_type' => 'integer',
        'prefecture_code' => 'integer',
        'min_salary' => 'integer',
        'max_salary' => 'integer',
        'status' => 'integer',
        'publish_start_at' => 'datetime',
        'publish_end_at' => 'datetime',
        'delete_flg' => 'integer',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function applications()
    {
        return $this->hasMany(JobApplication::class);
    }
}


