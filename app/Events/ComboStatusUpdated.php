<?php

namespace App\Events;

use App\Models\Combo;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

class ComboStatusUpdated implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public $combo;

    public function __construct(Combo $combo)
    {
        $this->combo = $combo;
    }

    public function broadcastOn()
    {
        return ['combo-status-channel'];
    }

    public function broadcastAs()
    {
        return 'combo-status-updated';
    }
}
