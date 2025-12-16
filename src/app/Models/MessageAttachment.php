<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MessageAttachment extends Model
{
    use HasFactory;

    protected $fillable = [
        'conversation_message_id',
        'file_path',
        'file_name',
        'file_size',
        'mime_type',
        'delete_flg',
    ];

    protected $casts = [
        'conversation_message_id' => 'integer',
        'file_size' => 'integer',
        'delete_flg' => 'integer',
    ];

    public function message()
    {
        return $this->belongsTo(ConversationMessage::class);
    }

    public function getUrlAttribute()
    {
        return asset('storage/' . $this->file_path);
    }
}

