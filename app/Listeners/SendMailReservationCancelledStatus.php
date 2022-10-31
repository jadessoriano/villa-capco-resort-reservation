<?php

namespace App\Listeners;

use App\Models\Reservation;
use App\Mail\ReservationCancelled;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\InteractsWithQueue;

class SendMailReservationCancelledStatus
{
    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        $guest = Reservation::find($event->transaction_no)->user;

        Mail::to($guest)->send(new ReservationCancelled($guest->first_name));
    }
}
