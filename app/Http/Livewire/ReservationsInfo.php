<?php

namespace App\Http\Livewire;

use App\Enums;
use App\Enums\ExtensionStatus;
use App\Enums\PackageName;
use App\Events\ReservationDeleted;
use App\Events\ReservationUpdated;
use App\Models\Accommodation;
use App\Models\Addon;
use App\Models\Catering;
use App\Models\Payment;
use App\Models\Package;
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

    public ?Package $extendedPackage = null;
    public ?Carbon $extendedDate = null;
    public string $extendedHours = '0';
    public ?Catering $catering = null;

    public bool $isNextSlotPackageForExtensionAvailable = false;
    public string $selectedPayment = '';

    protected $listeners = [
        'datePickerPicked' => 'changeRebookDate',
        'extend'
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

        $this->reservation->with('payments');
        $this->catering = $this->reservation->catering;

        $this->add_person_addon_id = Addon::where(
            'name', 'Additional Person'
        )->first()->id;

        $this->computeTotal();
        $this->collectReservedDates();
        $this->getNextSlotPackageForExtension();
        
        if (! $this->checkIfNextSlotPackageForExtensionIsAvailable()
            && $this->reservation->isExtensionOpen()) 
        {
            $this->reservation->extension_status = ExtensionStatus::unavailable->value;
            $this->reservation->save();
        }
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
        if (!$this->reservation->isCancelable()) return $this->dispatchBrowserEvent('reservation-not-cancellable');

        if ($this->show_cancel_reservation == false) {
            $this->show_cancel_reservation = true;
            return;
        }

        $this->reservation->update([
            'reserved_date' => null,
            'status_id' => Status::where('name', 'Cancelled')->pluck('id')->first()
        ]);
        $this->dispatchBrowserEvent('reservation-deleted');
        event(new ReservationDeleted($this->reservation->transaction_no));
    }

    private function computeTotal()
    {
        foreach($this->reservation->payments as $payment)
        {
            $this->total += $payment->amount_to_pay;
        }
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

    private function getNextSlotPackageForExtension(): void
    {
        if ($this->reservation->package->name === PackageName::morning->value) {
            /** 
             * If current schedule is Morning get the Evening schedule
             * for the extension.
             * */ 
            $this->extendedPackage = Package::whereName(PackageName::evening->value)->first();
            $this->extendedDate = $this->reservation->reserved_date;
        } else {
            /** 
             * If current schedule is Evening/Whole Day get the next Morning
             * for the extension.
             * */            
            $this->extendedPackage = Package::whereName(PackageName::morning->value)->first();
            $this->extendedDate = Carbon::parse($this->reservation->reserved_date)->addDay();
        }
    }

    private function checkIfNextSlotPackageForExtensionIsAvailable(): bool
    {
        $reservation = Reservation::where([
            ['package_id', $this->extendedPackage->id],
            ['reserved_date', '=', $this->extendedDate->format('Y-m-d')]
        ])->first();

        $this->isNextSlotPackageForExtensionAvailable = is_null($reservation);

        return $this->isNextSlotPackageForExtensionAvailable;
    }

    public function extend(): void
    {
        $this->reservation->extension_status = Enums\ExtensionStatus::approved->value;
        $this->reservation->save();
        
        $payment = new Payment();
        $payment->name = Enums\PaymentName::extension->value;
        $payment->reservation_transaction_no = $this->reservation->transaction_no;
        $payment->type = $this->selectedPayment;
        $payment->status = $this->selectedPayment === Enums\PaymentType::cod->value
            ? Enums\PaymentStatus::unpaid->value
            : Enums\PaymentStatus::paid->value;
        $payment->amount_to_pay = ($this->reservation->extended_hours * 500) * 100;
        
        $payment->save();

        $this->dispatchBrowserEvent('reservation-extended', ['accommodation' => $this->accommodation->name]);
    }
}
