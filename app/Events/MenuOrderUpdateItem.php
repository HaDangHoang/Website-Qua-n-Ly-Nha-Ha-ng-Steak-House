<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MenuOrderUpdateItem implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;
    public $item;

    public function __construct($item)
    {
        $this->item = $item;
    }

    public function broadcastOn()
    {
        return new Channel('order-updates');
    }
}
