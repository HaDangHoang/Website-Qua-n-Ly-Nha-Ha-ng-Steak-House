<?php

namespace App\Events;

use App\Models\Dishes;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UpdateDishStatus implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public $dish;

    public function __construct(Dishes $dish)
    {
        $this->dish = $dish;
    }

    public function broadcastOn()
    {
        return
            new Channel('dish-channel')
        ;
    }
}
