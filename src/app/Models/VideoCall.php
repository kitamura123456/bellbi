<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VideoCall extends Model
{
    use HasFactory;

    protected $fillable = [
        'conversation_id',
        'initiator_id',
        'recipient_id',
        'status',
        'started_at',
        'ended_at',
        'duration_seconds',
    ];

    protected $casts = [
        'conversation_id' => 'integer',
        'initiator_id' => 'integer',
        'recipient_id' => 'integer',
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
        'duration_seconds' => 'integer',
    ];

    public function conversation(): BelongsTo
    {
        return $this->belongsTo(Conversation::class);
    }

    public function initiator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'initiator_id');
    }

    public function recipient(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recipient_id');
    }

    /**
     * 通話を開始する
     */
    public function start(): void
    {
        $this->update([
            'status' => 'active',
            'started_at' => now(),
        ]);
    }

    /**
     * 通話を終了する
     */
    public function end(): void
    {
        $duration = $this->started_at 
            ? now()->diffInSeconds($this->started_at) 
            : null;

        $this->update([
            'status' => 'ended',
            'ended_at' => now(),
            'duration_seconds' => $duration,
        ]);
    }

    /**
     * 通話を拒否する
     */
    public function decline(): void
    {
        $this->update([
            'status' => 'declined',
            'ended_at' => now(),
        ]);
    }

    /**
     * 通話がアクティブかどうか
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * 通話が保留中かどうか
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }
}

