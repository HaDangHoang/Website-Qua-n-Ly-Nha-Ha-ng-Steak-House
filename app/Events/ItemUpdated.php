<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ItemUpdated implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public $items;
    public $noti;
    public $tableId;



    public function __construct($items, $noti, $tableId)
    {
        $this->items = $items;
        $this->noti = $noti;
        $this->tableId = $tableId;
    }

    public function broadcastOn()
    {
        return new Channel('items');
    }

    public function broadcastWith()
    {
        return [
            'orderItems' => $this->items,
            'noti' => $this->noti,
            'tableId' => $this->tableId,
        ];
    }
}
