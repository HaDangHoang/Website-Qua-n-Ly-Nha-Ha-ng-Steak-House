<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NotifyUsers implements ShouldBroadcast
{
    public $message;
    public $talbeId;


    public function __construct($message, $talbeId)
    {
        $this->message = $message;
        $this->talbeId = $talbeId;
    }

    public function broadcastOn()
    {
        return new Channel('notificationUsers-channel');
    }
}
