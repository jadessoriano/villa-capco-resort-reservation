<?php

namespace App\Listeners;

use App\Mail\ReservationReBooked;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\InteractsWithQueue;

class SendMailReservationReBookedStatus
{
    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        Mail::to($event->reservation->user)->send(new ReservationReBooked($event->reservation));
    }
}
