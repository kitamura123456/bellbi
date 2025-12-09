<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'profile_completed_flg',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => 'integer',
            'profile_completed_flg' => 'integer',
        ];
    }

    // ロール定義
    public const ROLE_PERSONAL = 1;      // 求職者・一般ユーザー
    public const ROLE_COMPANY = 2;       // 事業者（店舗アカウント）
    public const ROLE_ADMIN = 9;         // システム管理者

    public function company()
    {
        return $this->hasOne(Company::class);
    }

    public function jobApplications()
    {
        return $this->hasMany(JobApplication::class);
    }

    public function scoutProfile()
    {
        return $this->hasOne(ScoutProfile::class);
    }

    public function receivedScouts()
    {
        return $this->hasMany(ScoutMessage::class, 'to_user_id');
    }
}
