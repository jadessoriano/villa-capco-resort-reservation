<?php

namespace App\Listeners;

use App\Mail\ReservationBooked;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\InteractsWithQueue;

class SendMailReservationBookedStatus
{
    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        Mail::to($event->reservation->user)->send(new ReservationBooked($event->reservation));
    }
}
