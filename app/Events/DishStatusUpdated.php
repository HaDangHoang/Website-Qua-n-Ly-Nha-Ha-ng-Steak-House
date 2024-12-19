<?php

namespace App\Events;

use App\Models\Dishes;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

class DishStatusUpdated implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public $dish;

    public function __construct(Dishes $dish)
    {
        $this->dish = $dish;
    }

    public function broadcastOn()
    {
        return ['dish-status-channel'];
    }

    public function broadcastAs()
    {
        return 'dish-status-updated';
    }
}
