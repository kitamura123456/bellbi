<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_post_id',
        'user_id',
        'status',
        'message',
        'delete_flg',
    ];

    protected $casts = [
        'job_post_id' => 'integer',
        'user_id' => 'integer',
        'status' => 'integer',
        'delete_flg' => 'integer',
    ];

    public function jobPost()
    {
        return $this->belongsTo(JobPost::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}


