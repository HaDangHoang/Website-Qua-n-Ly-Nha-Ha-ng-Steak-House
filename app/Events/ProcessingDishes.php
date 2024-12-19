<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class ProcessingDishes implements ShouldBroadcastNow
{
    use InteractsWithSockets, SerializesModels;

    public $items;
    public $noti = null;


    public function __construct($items, $noti)
    {
        $this->items = $items->map(function ($item) {
            $item->formatted_updated_at = $item->updated_at->format('d-m-Y H:i');
            $item->formatted_created_at = $item->created_at->format('d-m-Y H:i');
            return $item;
        });
        $this->noti = $noti;
    }


    public function broadcastOn()
    {
        return new Channel('kitchen');
    }

}

