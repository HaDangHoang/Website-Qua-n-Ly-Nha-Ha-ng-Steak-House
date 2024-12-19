<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;


class MessageSentt implements ShouldBroadcastNow
{
    use InteractsWithSockets, SerializesModels;


    /**
     * Create a new event instance.
     */
    public $tables;

    public function __construct($tables)
    {
        $this->tables = $tables;
    }
    public function broadcastOn()
    {
        return
            new Channel('tablee')
        ;
    }
}
