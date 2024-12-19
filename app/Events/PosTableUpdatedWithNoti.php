<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;


class PosTableUpdatedWithNoti implements ShouldBroadcastNow
{
    use InteractsWithSockets, SerializesModels;

    public $orderItems;
    public $tableId;
    public $notiBtn;
    public $noti = null;


    public function __construct($orderItems, $tableId, $notiBtn,$noti)
    {
        $this->orderItems = $orderItems;
        $this->tableId = $tableId;
        $this->notiBtn = $notiBtn;
        $this->noti = $noti;
    }

    public function broadcastOn()
    {
        return new Channel('orders');
    }
}

