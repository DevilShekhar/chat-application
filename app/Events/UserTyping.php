<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserTyping implements ShouldBroadcast
{
    public $userId;
    public $username;
    public $receiverId;
    public $groupId;
    public $type;

    public function __construct($userId, $username, $receiverId = null, $groupId = null, $type)
    {
        $this->userId = $userId;
        $this->username = $username;
        $this->receiverId = $receiverId;
        $this->groupId = $groupId;
        $this->type = $type;
    }

    public function broadcastOn()
    {
        if ($this->type === 'private') {
            return new Channel('chat.' . $this->receiverId);
        }

        return new Channel('group.' . $this->groupId);
    }

    public function broadcastAs()
    {
        return 'UserTyping';
    }
}