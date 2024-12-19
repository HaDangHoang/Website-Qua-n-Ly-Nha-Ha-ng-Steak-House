<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;
use App\Models\Reservation;

class UpcomingReservationEvent implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public $reservation;

    public function __construct(Reservation $reservation)
    {
        $this->reservation = $reservation;
    }

    public function broadcastOn()
    {
        return ['reservations-channel'];
    }

    public function broadcastAs()
    {
        return 'upcoming-reservation';
    }
}
