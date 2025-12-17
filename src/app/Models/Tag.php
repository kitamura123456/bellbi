<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'delete_flg',
    ];

    protected $casts = [
        'delete_flg' => 'integer',
    ];

    public function jobPosts()
    {
        return $this->belongsToMany(JobPost::class, 'job_post_tags');
    }
}

