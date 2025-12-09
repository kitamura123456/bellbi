<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScoutProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'industry_type',
        'desired_job_category',
        'experience_years',
        'desired_work_style',
        'is_public',
        'delete_flg',
    ];

    protected $casts = [
        'user_id' => 'integer',
        'industry_type' => 'integer',
        'desired_job_category' => 'integer',
        'experience_years' => 'integer',
        'desired_work_style' => 'integer',
        'is_public' => 'integer',
        'delete_flg' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scoutMessages()
    {
        return $this->hasMany(ScoutMessage::class);
    }
}


