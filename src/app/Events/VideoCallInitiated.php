<?php

namespace App\Events;

use App\Models\VideoCall;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class VideoCallInitiated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public VideoCall $videoCall;

    /**
     * Create a new event instance.
     */
    public function __construct(VideoCall $videoCall)
    {
        $this->videoCall = $videoCall;
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
        return 'video-call.initiated';
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith(): array
    {
        return [
            'video_call' => [
                'id' => $this->videoCall->id,
                'conversation_id' => $this->videoCall->conversation_id,
                'initiator_id' => $this->videoCall->initiator_id,
                'recipient_id' => $this->videoCall->recipient_id,
                'status' => $this->videoCall->status,
                'initiator' => [
                    'id' => $this->videoCall->initiator->id,
                    'name' => $this->videoCall->initiator->name,
                ],
            ],
        ];
    }
}

