<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;


class PosTableUpdated implements ShouldBroadcastNow
{
    use InteractsWithSockets, SerializesModels;

    public $orderItems;
    public $tableId;
    public $notiBtn;
    public $checkoutBtn;



    public function __construct($orderItems, $tableId, $notiBtn, $checkoutBtn)
    {
        $this->orderItems = $orderItems;
        $this->tableId = $tableId;
        $this->notiBtn = $notiBtn;
        $this->checkoutBtn = $checkoutBtn;
    }

    public function broadcastOn()
    {
        return new Channel('order');
    }
}

