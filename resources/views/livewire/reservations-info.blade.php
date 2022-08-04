<div>
    <div class="h-fit p-3 bg-primary-bg rounded-lg">
        <x-card-title :value="'Package'" />
        <div class="flex items-start gap-10">
            <div class="mt-3 max-w-md">
                {{-- Reservation Details --}}
                <div class="flex items-start">
                    <div class="inline-block border-2 border-primary-fg w-fit p-3  mt-4">
                        <table>
                            <thead>
                                <tr>
                                    <th colspan="2"><x-card-subtitle :value="'Reservation Details'" /></th>
                                </tr>
                            </thead>
                            <tbody class="text-primary-fg text-left align-top">
                                <tr>
                                    <th class="pr-2">Accommodation</th>
                                    <th>Package</th>
                                </tr>
                                <tr>
                                    <td><x-tag class="mt-0 bg-secondary-bg" :value="$accommodation->name" /></td>
                                    <td><x-tag class="mt-0 bg-secondary-bg" :value="$package['name']" /></td>
                                </tr>
                                <tr>
                                    <th class="pt-3">Details</th>
                                </tr>
                                <tr>
                                    <td colspan="2"><x-tag class="mt-0 bg-secondary-bg" :value="$accommodation->details" /></td>
                                </tr>
                                <tr>
                                    <th class="pt-3">Schedule</th>
                                    <th class="pt-3">Reserved Date</th>
                                </tr>
                                <tr>
                                    <td>
                                        <x-schedule class="mt-0" :start_time="$package['start_time']" :end_time="$package['end_time']" />
                                    </td>
                                    <td><x-tag class="mt-0 bg-secondary-bg" :value="$reservation->reserved_date?->format('D M j, Y')" /></td>
                                </tr>
                                <tr>
                                    <th class="pt-3">No. of People</th>
                                    <th class="pt-3">Rate</th>
                                </tr>
                                <tr>
                                    <td><x-tag class="mt-0 bg-secondary-bg" :value="$reservation->no_of_people" /></td>
                                    <td><x-price class="inline" :value="$package['rate']" /></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Addons --}}
            <div class="mt-3 w-[400px]">
                <div class="inline-block border-2 border-primary-fg w-fit px-2 mt-10 mr-4">
                    <p>Addons:</p>
                    @foreach ($addons as $id=>$addon)
                        <div class="my-3 flex items-center gap-2">
                            @php
                                $excess_people = 0;
                                if ($id == $add_person_addon_id)
                                    $excess_people = $reservation->no_of_people - $package['max_people'];
                            @endphp
                            <div class="bg-secondary-bg rounded-lg text-white flex items-center">
                                <x-price class="inline !text-sm" :value="$addon['rate']" />
                                <x-label for="{{$addon['name']}}" :value="__($addon['name'])" class="inline !text-white mr-2" />
                                @if ($excess_people > 0)
                                    <x-label :value="__('Qty')" class="!text-white inline border-l-2 mr-2 pl-2" />
                                    <x-label :value="__($reservation->no_of_people - $package['max_people'])" class="!text-white inline mr-2" />
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <div class="flex justify-end items-center gap-3 h-fit mt-4 p-3 bg-primary-bg rounded-lg">
        <x-card-title :value="'Total'" />
        <x-price :value="$total" />
        <div class="{{ $show_cancel_reservation ? 'hidden' : '' }} border-l-2 border-primary-fg px-1 py-5"></div>
        {{-- Rebook --}}
        <x-button class="{{ $show_cancel_reservation ? 'hidden' : '' }} bg-yellow-400 font-bold" wire:click=rebook()>Rebook</x-button>

        {{-- Calendar --}}
        @if ($show_calendar)
            <x-button class="bg-red-500 font-bold" wire:click=cancelRebook()>Cancel</x-button>
            <div class="inline-block border-2 border-primary-fg w-fit p-3 pl-1 mt-4">
                <x-label for="reserved_date" :value="__('*should be at least one week ahead')" class="block text-right text-primary-fg text-xs" />
                <x-label for="reserved_date" :value="__('Reservation date')" class="inline pl-2" />
                <x-input id="reserved_date" class="inline w-fit py-0 px-1" :value="$rebook_date" required autofocus />
            </div>
        @endif

        {{-- Cancel Reservation --}}
        @if ($reservation->isCancelable())
            <div class="{{ $show_calendar ? 'hidden' : '' }} border-l-2 border-primary-fg px-1 py-5"></div>
            <x-button class="{{ $show_cancel_reservation || $show_calendar ? 'hidden' : '' }} bg-red-500 font-bold"
                wire:click=cancelReservation()>Cancel Reservation</x-button>

            {{-- Confirmation Button --}}
            @if ($show_cancel_reservation)
                <x-button class="bg-green-700 font-bold" wire:click=cancelReservation()>Proceed</x-button>
                <x-button class="bg-red-500 font-bold" wire:click=hideCancelReservation()>Cancel</x-button>
            @endif
        @endif
    </div>

    {{-- Receipt Link --}}
    <a id="receipt-link" class="hidden" href="{{asset('storage/' . $reservation->receipt_path)}}" target="_blank"></a>
</div>

<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/pikaday/css/pikaday.css">
<script src="https://cdn.jsdelivr.net/npm/pikaday/pikaday.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
<script>
    let nextWeekTime = new Date().getTime() + 7 * 24 * 60 * 60 *1000;
    let disabledDates = [];

    document.addEventListener("DOMContentLoaded", () => {
        Livewire.hook('message.processed', (el, component) => {
            disabledDates = @this.disabledDates;
        })
    });
    
    window.addEventListener('calendar-visible', event => {
        var picker = new Pikaday({
            field: document.getElementById('reserved_date'),
            firstDay: 0, /* start with Sunday */
            defaultDate: new Date(nextWeekTime),
            minDate: new Date(nextWeekTime),
            maxDate: new Date(2022, 12, 31),

            disableDayFn: function (date) {
                let formattedDate = moment(date).format('MM/DD/YYYY');
                return disabledDates.includes(formattedDate);
            },

            onSelect: function(date) {
                var datePicker = document.getElementById('reserved_date');
                datePicker.value = moment(date).format('Do MMMM YYYY');
                Livewire.emit('datePickerPicked', datePicker.value);
            }
        });
    })
    
    window.addEventListener('reservation-updated', event => {
        window.location.reload();
        alert("Reservation has been successfully rebooked.");
        document.getElementById('receipt-link').click();
    })
    
    window.addEventListener('reservation-deleted', event => {
        window.location.reload();
        alert("Reservation has been successfully cancelled.");
    })
</script>