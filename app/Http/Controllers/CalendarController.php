<?php

namespace App\Http\Controllers;

use App\Enums\ExtensionStatus;
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
                public $end = '';

                public function __construct(Reservation $reservation) {
                    $this->start = Carbon::parse($reservation->reserved_date)->format('Y-m-d');

                    $timeDifference = ((int) Carbon::parse($reservation->package->end_time)->format('H:i:s') - (int) Carbon::parse($reservation->package->start_time)->format('H:i:s'));
                    $hours = $timeDifference === 0 ? 24 : $timeDifference;
                    $extendedHours = $reservation->extension_status === ExtensionStatus::approved->value ? $reservation->extended_hours : 0;

                    $this->title = Carbon::parse($reservation->package->start_time)->format('g:i A') . ' - ' .Carbon::parse($reservation->package->end_time)->addHours($extendedHours)->format('g:i A');

                    $hours = abs($hours) + $extendedHours;
                    
                    $computeEndTime = Carbon::parse(Carbon::parse($reservation->reserved_date)->format('Y-m-d'). ' ' .Carbon::parse($reservation->package->start_time)->format('H:i:s'))->addHours($hours);

                    $this->end = $computeEndTime->addDay()->format('Y-m-d');
                }
            };

            $dates[] = $obj;
        }

        return view('app.calendar', compact('dates'));
    }
}
