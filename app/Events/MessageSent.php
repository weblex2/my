<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class MessageSent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;
    public $channel;

    /**
     * Create a new event instance.
     */
    public function __construct($message, $channel = 'chat')
    {
        $this->message = $message;
        $this->channel = $channel;
        //Log::channel('mylog')->info($message);
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array
     */
    public function broadcastOn(): array
    {
        return [
            new Channel($this->channel), 
        ];
    }

    public function broadcastAs(): string
    {
        return 'message.sent';
    }
}