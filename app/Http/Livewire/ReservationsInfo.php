<?php

namespace App\Http\Livewire;

use App\Events\ReservationDeleted;
use App\Events\ReservationUpdated;
use App\Models\Accommodation;
use App\Models\Addon;
use App\Models\Reservation;
use App\Models\Status;
use Carbon\Carbon;
use Livewire\Component;

class ReservationsInfo extends Component
{
    public $reservation;
    public $accommodation;
    public $package;
    public $addons = []; /* For computation. */
    public $total = 0;
    public $disabledDates = []; /* For JS. For Rebooking. */
    public $add_person_addon_id; /* For blade in displaying quantity. */

    public $show_calendar = false;
    public $show_cancel_reservation = false;
    public $rebook_date;

    protected $listeners = [
        'datePickerPicked' => 'changeRebookDate',
    ];

    public function render()
    {
        return view('livewire.reservations-info');
    }

    public function mount(Reservation $current_reservation)
    {
        $this->reservation = $current_reservation;

        $accommodation_id = $current_reservation->accommodation_id;
        $this->accommodation = Accommodation::find($accommodation_id);

        $package_id = $current_reservation->package_id;
        $this->package = $this->accommodation
            ->packages()
            ->wherePivot('package_id', $package_id)
            ->get()
            ->map(fn ($item) => [
                'id' => $item['id'],
                'name' => $item['name'],
                'start_time' => $item['start_time'],
                'end_time' => $item['end_time'],
                'rate' => $item->pivot['rate'],
                'max_people' => $item->pivot['max_people'],
            ])
            ->first();

        $this->addons = $current_reservation
            ->addons()
            ->get()
            ->mapWithKeys(fn ($item) => [
                $item['id'] => [
                    'name' => $item['name'],
                    'rate' => $item['rate'],
                    'quantity' => $item->pivot['quantity']
                ]
            ])->toArray();

        $this->add_person_addon_id = Addon::where(
            'name', 'Additional Person'
        )->first()->id;

        $this->computeTotal();
        $this->collectReservedDates();
    }

    public function cancelRebook() {
        $this->show_calendar = false;
    }

    public function changeRebookDate($rebook_date) {
        $this->rebook_date = $rebook_date;
    }

    public function rebook()
    {
        if ($this->show_calendar == false) {
            $this->show_calendar = true;
            $this->dispatchBrowserEvent('calendar-visible');
            return;
        }

        $this->reservation->update([
            'reserved_date' => Carbon::parse($this->rebook_date),
            'status_id' => Status::where('name', 'Rebooked')->pluck('id')->first()
        ]);
        $this->dispatchBrowserEvent('reservation-updated');
        event(new ReservationUpdated($this->reservation));
    }

    public function hideCancelReservation() {
        $this->show_cancel_reservation = false;
    }

    public function cancelReservation()
    {
        if ($this->show_cancel_reservation == false) {
            $this->show_cancel_reservation = true;
            return;
        }
        
        $this->reservation->update([
            'reserved_date' => null,
            'status_id' => Status::where('name', 'Cancelled')->pluck('id')->first()
        ]);
        $this->dispatchBrowserEvent('reservation-deleted');
        event(new ReservationDeleted($this->reservation));
    }

    private function computeTotal()
    {
        $this->total = $this->package['rate'];
        // TODO: Add condition for id if add_person_addon_id then create global var for excess_people
        /**
         * The $this->addons here does not have to be attached to reservation since
         * the data are static and only needed to be displayed.
         */
        foreach ($this->addons as $addon)
            $this->total += $addon['quantity'] * $addon['rate'];
    }

    private function collectReservedDates()
    {
        $this->disabledDates = Reservation::where([
                ['package_id', $this->package['id']],
                ['accommodation_id', $this->accommodation->id],
                ['reserved_date', '>', Carbon::now()->addWeek()->toDateString()],
            ])
            ->pluck('reserved_date')
            ->map(fn ($item) => $item->format('m/d/Y'))
            ->toArray();
    }
}
