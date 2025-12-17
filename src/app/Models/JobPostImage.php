<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobPostImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_post_id',
        'path',
        'sort_order',
        'delete_flg',
    ];

    protected $casts = [
        'job_post_id' => 'integer',
        'sort_order' => 'integer',
        'delete_flg' => 'integer',
    ];

    public function jobPost()
    {
        return $this->belongsTo(JobPost::class);
    }
}
