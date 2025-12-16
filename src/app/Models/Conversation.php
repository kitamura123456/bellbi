<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'company_id',
        'job_application_id',
        'scout_message_id',
        'delete_flg',
    ];

    protected $casts = [
        'user_id' => 'integer',
        'company_id' => 'integer',
        'job_application_id' => 'integer',
        'scout_message_id' => 'integer',
        'delete_flg' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function jobApplication()
    {
        return $this->belongsTo(JobApplication::class);
    }

    public function scoutMessage()
    {
        return $this->belongsTo(ScoutMessage::class);
    }

    public function messages()
    {
        return $this->hasMany(ConversationMessage::class)->where('delete_flg', 0)->orderBy('created_at', 'asc');
    }

    public function latestMessage()
    {
        return $this->hasOne(ConversationMessage::class)->where('delete_flg', 0)->latestOfMany();
    }

    /**
     * 応募またはスカウトに関連する会話を取得または作成
     */
    public static function getOrCreateForApplication(JobApplication $application)
    {
        return static::firstOrCreate(
            [
                'user_id' => $application->user_id,
                'company_id' => $application->jobPost->company_id,
                'job_application_id' => $application->id,
            ],
            [
                'delete_flg' => 0,
            ]
        );
    }

    public static function getOrCreateForScout(ScoutMessage $scout)
    {
        return static::firstOrCreate(
            [
                'user_id' => $scout->to_user_id,
                'company_id' => $scout->from_company_id,
                'scout_message_id' => $scout->id,
            ],
            [
                'delete_flg' => 0,
            ]
        );
    }
}

