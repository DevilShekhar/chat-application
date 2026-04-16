<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcastNow
{
    use SerializesModels;

    public $message;

    public function __construct($message)
    {
        $this->message = $message->load('sender');
    }

    public function broadcastOn()
    {
        if ($this->message->type === 'private') {
            return [
                new Channel('chat.' . $this->message->receiver_id),
                new Channel('chat.' . $this->message->sender_id),
            ];
        }

        return new Channel('group.' . $this->message->group_id);
    }

    public function broadcastAs()
    {
        return 'MessageSent';
    }
}