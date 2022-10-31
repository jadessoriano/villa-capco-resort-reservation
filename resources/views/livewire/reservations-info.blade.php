<div x-data="{isChecked: false}">
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
                                <tr>
                                    <th class="pt-3">Extension (<span>{{ $this->reservation->extension_status ?? 'N/A' }}</span>)</th>
                                    <th class="pt-3"></th>
                                </tr>
                                <tr>
                                    <td><x-tag class="mt-0 bg-secondary-bg" :value="$extendedPackage->name" /></td>
                                    <td>
                                        <x-schedule class="mt-0" :start_time="$extendedPackage->start_time" :end_time="$extendedPackage->end_time" />
                                    </td>
                                </tr>
                                <tr>
                                    <td><x-tag class="mt-0 bg-secondary-bg" :value="$extendedDate?->format('D M j, Y')" /></td>
                                    <td>
                                        @if (! $this->reservation->isExtensionRequested()
                                            && $this->isNextSlotPackageForExtensionAvailable)
                                            <form action="{{ route('guest.extension.request') }}" method="POST">
                                                @csrf
                                                <input name="reservationNumber" value="{{ $this->reservation->transaction_no }}" type="text" hidden>
                                                <input name="extendedPackageId" value="{{ $this->extendedPackage->id }}" type="text" hidden>
                                                <input name="extendedDate" value="{{ $this->extendedDate->format('Y-m-d') }}" type="text" hidden>

                                                <x-button class="bg-green-600 font-bold" type="submit">REQUEST EXTENSION</x-button>
                                            </form>
                                        @endif 
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="mt-3 w-[400px]">
                {{-- AddOns --}}
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

                {{-- Catering --}}
                <div class="inline-block border-2 border-primary-fg w-fit px-2 mt-10 mr-4">
                    <p>Catering Package:</p>
                    <div class="my-3 flex items-center gap-2">
                        <div class="bg-secondary-bg rounded-lg text-white flex items-center">
                            <x-price class="inline" :value="$this->catering->rate" />
                            <x-tag class="mt-0 bg-secondary-bg" :value="$this->catering->name" />
                        </div>
                    </div>
                </div>

                {{-- Payment --}}
                <div class="inline-block border-2 border-primary-fg w-fit px-2 mt-10 mr-4">
                    <p>Payments:</p>
                    <table>
                        <thead class="text-primary-fg text-left align-top">
                            <tr>
                                <th>Name</th>
                                <th>Type</th>
                                <th>Amount</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($this->reservation->payments as $payment)
                                <tr>
                                    <td><x-tag class="mt-0 bg-secondary-bg" :value="$payment->name->value" /></td>
                                    <td><x-tag class="mt-0 bg-secondary-bg" :value="$payment->type->value" /></td>
                                    <td><x-price class="inline" :value="$payment->amount_to_pay" /></td>
                                    <td><x-tag class="mt-0 bg-secondary-bg" :value="$payment->status->value" /></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            
        </div>
    </div>

    {{-- Payments --}}
    <div x-data="{ show: false, selectedPayment: @entangle('selectedPayment') }">
        <div class="{{ $reservation->extension_status == \App\Enums\ExtensionStatus::confirming->value
                            ? '' 
                            : 'hidden' }} h-fit mt-4 p-3 bg-primary-bg rounded-lg">
            <div>
                <x-card-title :value="'Select Payment Method'" />
                <div class="flex items-center">
                    <div class="flex h-5 items-center">
                      <input type="checkbox" class="h-4 w-4 rounded border-gray-300 text-primary-fg focus:ring-primary-bg cursor-pointer" @click="isChecked = ! isChecked">
                    </div>
                    <x-terms />
                </div>
                <div class="grid grid-cols-2 gap-4 mt-4">
                    <label :class="isChecked ? 'pointer-events-auto opacity-100' : 'pointer-events-none opacity-50'" class="group relative border rounded-md py-3 px-4 flex items-center justify-center text-sm font-medium uppercase hover:bg-gray-50 focus:outline-none sm:flex-1 bg-white shadow-sm text-gray-900 cursor-pointer">
                        <input 
                            x-model="selectedPayment" 
                            @click="show = false"
                            type="radio" 
                            name="payment-choice" 
                            value="{{\App\Enums\PaymentType::cod->value}}" 
                            class="sr-only" 
                            aria-labelledby="size-choice-2-label">
                        <span id="size-choice-2-label">Cash on Delivery (COD)</span>
                        <span class="pointer-events-none absolute -inset-px rounded-md border-2" :class="selectedPayment == '{{\App\Enums\PaymentType::cod->value}}' ? 'border-primary-fg' : 'border-transparent'" aria-hidden="true"></span>
                    </label>
                    <label :class="isChecked ? 'pointer-events-auto opacity-100' : 'pointer-events-none opacity-50'" class="group relative border rounded-md py-3 px-4 flex items-center justify-center text-sm font-medium uppercase hover:bg-gray-50 focus:outline-none sm:flex-1 bg-white shadow-sm text-gray-900 cursor-pointer">
                        <input 
                            x-model="selectedPayment" 
                            @click="show = ! show"
                            type="radio" 
                            name="payment-choice" 
                            value="{{\App\Enums\PaymentType::paypal->value}}" 
                            class="sr-only" 
                            aria-labelledby="size-choice-2-label">
                        <span id="size-choice-2-label">PayPal</span>
                        <span class="pointer-events-none absolute -inset-px rounded-md border-2" :class="selectedPayment == '{{\App\Enums\PaymentType::paypal->value}}' ? 'border-primary-fg' : 'border-transparent'" aria-hidden="true"></span>
                    </label>
                </div>
            </div>
            <div class="mt-6" :class="isChecked && show || 'hidden'" wire:ignore>
                <div id="paypal-button-container"></div>
            </div>
        </div>
    </div>

    <div class="flex justify-end items-center gap-3 h-fit mt-4 p-3 bg-primary-bg rounded-lg">
        <x-card-title :value="'Total'" />
        <x-price :value="$total" />

        {{-- Rebook --}}
        @if ($reservation->isExtensionOpen())
            <div class="{{ $show_cancel_reservation ? 'hidden' : '' }} border-l-2 border-primary-fg px-1 py-5"></div>

            <x-button class="{{ $show_cancel_reservation ? 'hidden' : '' }} bg-yellow-600 font-bold" wire:click=rebook()>Rebook</x-button>
        @endif

        {{-- Extend --}}
        @if ($reservation->isExtensionConfirming()
            && $selectedPayment == \App\Enums\PaymentType::cod->value)
            <div class="{{ $show_cancel_reservation ? 'hidden' : '' }} border-l-2 border-primary-fg px-1 py-5"></div>

            <x-button class="{{ $show_cancel_reservation ? 'hidden' : '' }} bg-yellow-600 font-bold" wire:click=extend()>Extend</x-button>
        @endif

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

@push('styles')
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/pikaday/css/pikaday.css">
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/pikaday/pikaday.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
    <script src="https://www.paypal.com/sdk/js?client-id=AWFlRJqqlVBzhfQZcNZv8OyfkRAH7jFGcGIYq2Zs1jQP-oOgIAeXpKju6viuf8Aqu--ilfUTk1LfAhBo&currency=PHP"></script>
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

        window.addEventListener('reservation-extended', event => {
            if(alert(event.detail.accommodation + " has been successfully extended.")){}
            else {
                window.location.reload();
            }
        })
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            paypal.Buttons({    
                style: {
                    layout: "vertical",
                    color: "blue",
                    shape: "pill",
                    label: "pay",
                },
                createOrder: function (data, actions) {
                    return actions.order.create({
                        purchase_units: [
                        {
                            amount: {
                            value: 1,
                            // @this.package['rate'] / 100
                            },
                        },
                        ],
                    });
                },
                onApprove: function (data, actions) {
                    return actions.order.capture().then(function (details) {
                        window.livewire.emit('extend')
                    });
                },
            }).render('#paypal-button-container');
        });
    </script>
@endpush
