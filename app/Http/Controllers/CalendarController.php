<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class CalendarController extends Controller
{
    public function index()
    {
        $dates = [];

        $reservations = Reservation::with('package')->where('status_id', 1)->whereDate('reserved_date', '>=', Carbon::now()->addWeek())->get();

        foreach($reservations as $reservation)
        {
            $obj = new class($reservation) {
                public $title = '';
                public $start = '';

                public function __construct(Reservation $reservation) {
                    $this->title = Carbon::parse($reservation->package->start_time)->format('g:i A') . ' - ' .Carbon::parse($reservation->package->end_time)->format('g:i A');

                    $this->start = Carbon::parse($reservation->reserved_date)->format('Y-m-d');
                }
            };

            $dates[] = $obj;
        }

        return view('app.calendar', compact('dates'));
    }
}
