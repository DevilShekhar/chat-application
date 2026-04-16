<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class UserStatusChanged implements ShouldBroadcast
{
    public $userId;
    public $status;

    public function __construct($userId, $status)
    {
        $this->userId = $userId;
        $this->status = $status;
    }

    public function broadcastOn()
    {
        return new Channel('user-status');
    }

    public function broadcastAs()
    {
        return 'status.changed';
    }
}