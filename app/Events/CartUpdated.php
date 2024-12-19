<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CartUpdated implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public $item;
    public $tableId;

    public $amount;

    public function __construct($item, $amount, $tableId)
    {
        $this->item = $item;
        $this->amount = $amount;
        $this->tableId = $tableId;
    }
    public function broadcastOn()
    {
        return
            new Channel('menuOrder')
        ;
    }
}
