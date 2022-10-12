<?php

namespace App\Http\Controllers;

use App\Enums\ExtensionStatus;
use App\Models\Reservation;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class ExtensionController extends Controller
{
    public function test(Request $request)
    {
        $reservation = Reservation::where('transaction_no', $request->reservationNumber)->first();

        $reservation->extended_package_id = $request->extendedPackageId;
        $reservation->extension_status = ExtensionStatus::confirming->value;
        $reservation->extension_date = $request->extendedDate;
        $reservation->save();

        return Redirect::route('reservations');
    }
}