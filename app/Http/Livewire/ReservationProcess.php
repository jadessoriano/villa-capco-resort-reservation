<?php

namespace App\Http\Livewire;

use App\Events\ReservationCreated;
use App\Models\Accommodation;
use App\Models\Addon;
use App\Models\Reservation;
use App\Models\Status;
use Carbon\Carbon;
use Livewire\Component;

class ReservationProcess extends Component
{
    public $reservation = [];
    public $package_names = [];
    /**
     * accommodation
     * details
     * package
     * start_time
     * end_time
     * rate
     * max_people
     */
    public $summary_details = []; /* including accommodation. */
    public $selected_accommodation_id; /* for summary */
    public $selected_package_id; /* for display of dropdown */
    public $selected_addons = []; /* for computation */
    public $accommodations = [];
    public $addons = [];
    public $no_of_people;
    public $reserved_date;
    public $total = 0;
    public $add_person_addon_id;
    public $function_hall_addon_id;
    public $function_hall_accommodation_id;

    public $receipt_path;
    public $disabledDates = []; /* For JS */

    protected $listeners = [
        'datePickerPicked' => 'reservedDateChanged',
    ];

    public function render()
    {
        return view('livewire.reservation-process');
    }

    public function mount(?int $accommodation_id, ?int $package_id)
    {
        $this->accommodations = Accommodation::all()
            ->mapWithKeys(fn ($item) => [
                $item['id'] => [
                    'name' => $item['name'],
                    'details' => $item['details']
                ]
            ])->toArray();

        $this->addons = Addon::all()
            ->mapWithKeys(fn ($item) => [
                $item['id'] => [
                    'name' => $item['name'],
                    'rate' => $item['rate']
                ]
            ])->toArray();

        // Save in cache to prevent querying everytime
        // accommodation - function hall is selected
        $this->function_hall_addon_id = Addon::where(
            'name', 'Function Hall (if you rent Pools 1-4)'
        )->first()->id;
        $this->function_hall_accommodation_id = Accommodation::where(
            'name', 'Function Hall'
        )->first()->id;
        // Reference for the multiple quantities addon.
        $this->add_person_addon_id = Addon::where(
            'name', 'Additional Person'
        )->first()->id;

        // Check if called through the `/accommodations`
        if ($accommodation_id != null)
            $this->queryPackages($accommodation_id);

        if ($package_id != null)
            $this->showSummary($package_id);
    }

    public function queryPackages(int $accommodation_id)
    {
        $this->package_names = Accommodation::find($accommodation_id)
            ->packages()->pluck('name', 'id');
        $this->selected_accommodation_id = $accommodation_id;
        $this->selected_package_id =  null;
        $this->summary_details =  null;

        if ($accommodation_id == $this->function_hall_accommodation_id)
            unset($this->addons[$this->function_hall_addon_id]);
        else if (!array_key_exists($this->function_hall_addon_id, $this->addons))  {
            $addon = Addon::find($this->function_hall_addon_id)->first();
            $this->addons[$this->function_hall_addon_id] = [
                'name' => $addon['name'],
                'rate' => $addon['rate']
            ];
        }
    }

    public function showSummary(int $package_id)
    {
        $package = Accommodation::find($this->selected_accommodation_id)
            ->packages()->wherePivot('package_id', $package_id)->first();
        $this->selected_package_id = $package_id;

        $this->summary_details = [
            'accommodation' => $this->accommodations[$this->selected_accommodation_id]['name'],
            'details' => $this->accommodations[$this->selected_accommodation_id]['details'],
            'package' => $package->name,
            'start_time' => $package->start_time,
            'end_time' => $package->end_time,
            'rate' => $package->pivot->rate,
            'max_people' => $package->pivot->max_people,
        ];

        $this->disabledDates = Reservation::where([
                ['package_id', $package_id],
                ['accommodation_id', $this->selected_accommodation_id],
                ['reserved_date', '>', Carbon::now()->addWeek()->toDateString()],
            ])
            ->pluck('reserved_date')
            ->map(fn ($item) => $item->format('m/d/Y'))
            ->toArray();

        $this->computeTotal();
    }

    public function addAddon($addon_id, $is_checked) {
        /**
         * $this->selected_addons CAN NOT store the 'rate' field since it would cause
         * an error when attaching to the reservation instance.
         * Use the $this->addons[<addon_id>]['rate'] to get the rate of the selected addon.
         */
        $this->selected_addons[$addon_id] = [
            'quantity' => $is_checked ? 1 : 0
        ];

        $this->computeTotal();
    }

    public function numberOfPeopleChanged($no_of_people)
    {
        $this->no_of_people = $no_of_people;
        $quantity = max(0, $no_of_people - $this->summary_details['max_people']);
        $this->selected_addons[$this->add_person_addon_id]['quantity'] = $quantity;
        
        $this->computeTotal();
    }

    public function reservedDateChanged($reserved_date)
    {
        $this->reserved_date = $reserved_date;
    }

    public function reserve()
    {
        $reservation = Reservation::create([
            'accommodation_id' => $this->selected_accommodation_id,
            'package_id' => $this->selected_package_id,
            'user_id' => auth()->user()->id,
            'status_id' => Status::where('name', 'Booked')->first()->id,
            'no_of_people' => $this->no_of_people,
            'amount_to_pay' => $this->total,
            'mode_of_payment' => "Cash",
            'reserved_date' => Carbon::parse($this->reserved_date),
        ]);
        $reservation->addons()->attach($this->selected_addons);
        $qr_code_path = Reservation::getQrCodeFilepathFor($reservation->transaction_no);
        $receipt_path = Reservation::getReceiptFilepathFor($reservation->transaction_no);
        $reservation->update([
            'qr_code_path' => $qr_code_path,
            'receipt_path' => $receipt_path,
        ]);
        $this->receipt_path = asset('storage/' . $receipt_path);

        $this->dispatchBrowserEvent('reservation-created', ['accommodation' => $this->summary_details['accommodation']]);
        event(new ReservationCreated($reservation));
    }

    private function computeTotal()
    {
        $this->total = $this->summary_details['rate'];
        /**
         * Take note of the $this->addons[$id].
         * The $this->selected_addons DOES NOT store the 'rate' as it will cause
         * an error when attaching to the reservation instance.
         * Use the $id instead to get the $this->addons[$id]['rate'] since the
         * key of the selected_addons or the $id is the index of that addon
         * in the $this->addons.
         */
        foreach ($this->selected_addons as $id=>$addon)
            $this->total += $addon['quantity'] * $this->addons[$id]['rate'];
    }
}
