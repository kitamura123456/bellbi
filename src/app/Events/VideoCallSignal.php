<?php

namespace App\Events;

use App\Models\VideoCall;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class VideoCallSignal implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public VideoCall $videoCall;
    public int $senderId;
    public $signal;

    /**
     * Create a new event instance.
     */
    public function __construct(VideoCall $videoCall, int $senderId, $signal)
    {
        $this->videoCall = $videoCall;
        $this->senderId = $senderId;
        $this->signal = $signal;
    }

    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('conversation.' . $this->videoCall->conversation_id),
        ];
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'video-call.signal';
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith(): array
    {
        return [
            'video_call_id' => $this->videoCall->id,
            'sender_id' => $this->senderId,
            'signal' => $this->signal,
        ];
    }
}

