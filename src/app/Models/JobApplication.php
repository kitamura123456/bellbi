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
        'interview_date',
        'message',
        'viewed_at',
        'delete_flg',
    ];

    protected $casts = [
        'job_post_id' => 'integer',
        'user_id' => 'integer',
        'status' => 'integer',
        'interview_date' => 'date',
        'viewed_at' => 'datetime',
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

    public function conversation()
    {
        return $this->hasOne(Conversation::class);
    }

    /**
     * 未読かどうかを判定
     */
    public function isUnread(): bool
    {
        return is_null($this->viewed_at);
    }
}


