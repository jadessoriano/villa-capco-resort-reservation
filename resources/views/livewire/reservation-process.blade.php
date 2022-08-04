<div>
    <div class="h-fit p-3 bg-primary-bg rounded-lg">
        <x-card-title :value="'Package'" />
        <div class="flex items-start gap-10">
            <div class="mt-3 max-w-md">
                {{-- Accommodations --}}
                <div class="inline-block border-2 border-primary-fg w-fit px-2">
                    <x-dropdown align="left" width="48">
                        <x-slot name="trigger">
                            <button class="flex items-center text-sm font-medium text-primary-fg hover:text-secondary-fg hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out">
                                <div>{{ $selected_accommodation_id ? $accommodations[$selected_accommodation_id]['name'] : 'Select an Accommodation' }}</div>

                                <div class="ml-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            @foreach ($accommodations as $id=>$accommodation)
                                <div class="m-2">
                                    <x-button id="{{$accommodation['name']}}" class="w-full flex flex-col" wire:click="queryPackages({{$id}})">
                                        <p class="text-white text-md font-bold">{{ $accommodation['name'] }}</p>
                                        <p class="text-[8px]">{{ $accommodation['details'] }}</p>
                                    </x-button>
                                </div>
                            @endforeach
                        </x-slot>
                    </x-dropdown>
                </div>

                @if ($package_names)
                    {{-- Packages --}}
                    <div class="inline-block border-2 border-primary-fg w-fit px-2 ml-4">
                        <x-dropdown align="left" width="48">
                            <x-slot name="trigger">
                                <button class="flex items-center text-sm font-medium text-primary-fg hover:text-secondary-fg hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out">
                                    <div>{{ $selected_package_id ? $package_names[$selected_package_id] : 'Select a Package' }}</div>

                                    <div class="ml-1">
                                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                @foreach ($package_names as $id=>$package_name)
                                    <div class="m-2">
                                        <x-button class="w-full flex flex-col" wire:click="showSummary({{$id}})">
                                            {{ $package_name }}
                                        </x-button>
                                    </div>
                                @endforeach
                            </x-slot>
                        </x-dropdown>
                    </div>

                    {{-- Summary Details --}}
                    <div class="flex items-start">
                        @if ($summary_details)
                            <div class="inline-block border-2 border-primary-fg w-fit p-3  mt-4">
                                <table>
                                    <thead>
                                        <tr>
                                            <th colspan="3"><x-card-subtitle :value="'Summary Details'" /></th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-primary-fg text-left align-top">
                                        <tr>
                                            <th class="pr-2">Accommodation</th>
                                            <th>Package</th>
                                        </tr>
                                        <tr>
                                            <td><x-tag class="mt-0 bg-secondary-bg" :value="$summary_details['accommodation']" /></td>
                                            <td><x-tag class="mt-0 bg-secondary-bg" :value="$summary_details['package']" /></td>
                                        </tr>
                                        <tr>
                                            <th class="pt-3">Details</th>
                                        </tr>
                                        <tr>
                                            <td colspan="3"><x-tag class="mt-0 bg-secondary-bg" :value="$summary_details['details']" /></td>
                                        </tr>
                                        <tr>
                                            <th class="pt-3">Schedule</th>
                                            <th class="pt-3 pl-3">Max People</th>
                                            <th class="pt-3 pl-3">Rate</th>
                                        </tr>
                                        <tr>
                                            <td>
                                                <x-schedule class="mt-0" :start_time="$summary_details['start_time']" :end_time="$summary_details['end_time']" />
                                            </td>
                                            <td class="pl-3"><x-tag class="mt-0 bg-secondary-bg" :value="$summary_details['max_people']" /></td>
                                            <td class="pl-3"><x-tag class="mt-0 bg-secondary-bg" :value="'₱ ' . number_format(App\Facades\Format::moneyForDisplay($summary_details['rate']), 2)" /></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                @endif
            </div>

            {{-- Addons --}}
            <div class="mt-3 w-[400px]">
                @if ($summary_details)
                    <div class="inline-block border-2 border-primary-fg w-fit px-2 mt-10 mr-4">
                        <p>Select any services you want to add:</p>
                        @foreach ($addons as $id=>$addon)
                            <div class="my-3 flex items-center gap-2">
                                @php
                                    $excess_people = 0;
                                    if ($id == $add_person_addon_id)
                                        $excess_people = $no_of_people - $summary_details['max_people'];
                                @endphp
                                @if ($excess_people > 0)
                                    <x-input id="{{$addon['name']}}" class="inline py-0 px-3" wire:click="addAddon({{$id}}, $event.target.checked)" type="checkbox" name="{{$addon['name']}}" :value="$id" checked />
                                @else
                                    <x-input id="{{$addon['name']}}" class="inline py-0 px-3" :disabled="$id == $add_person_addon_id" wire:click="addAddon({{$id}}, $event.target.checked)" type="checkbox" name="{{$addon['name']}}" :value="$id" />
                                @endif
                                <div>
                                    <x-label for="{{$addon['name']}}" :value="'(₱ ' . number_format(App\Facades\Format::moneyForDisplay($addon['rate']), 2) . ')'" class="inline w-24" />
                                    <x-label for="{{$addon['name']}}" :value="__($addon['name'])" class="inline" />
                                    @if ($excess_people > 0)
                                        <x-label :value="__('Qty')" class="inline border-l-2 mx-2 pl-2 border-secondary-bg" />
                                        <x-label :value="__($no_of_people - $summary_details['max_people'])" class="inline" />
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <div class="{{$summary_details ? '' : 'hidden'}} flex gap-5 items-center" >
            {{-- No of People --}}
            <div class="inline-block border-2 border-primary-fg w-fit p-3 pl-1 mt-4">
                <x-label for="no_of_people" :value="__('No. of people')" class="inline  pl-2" />
                <x-input id="no_of_people" class="inline w-[50px] py-0 px-1" type="number" min=1 name="no_of_people" :value="old('no_of_people')" wire:change="numberOfPeopleChanged($event.target.value)" required autofocus />
            </div>

            {{-- Reserved Date --}}
            <div class="inline-block border-2 border-primary-fg w-fit p-3 pl-1 mt-4">
                <x-label for="reserved_date" :value="__('*should be at least one week ahead')" class="block text-right text-primary-fg text-xs" />
                <x-label for="reserved_date" :value="__('Reservation date')" class="inline pl-2" />
                <x-input id="reserved_date" class="inline w-fit py-0 px-1" :value="$reserved_date" required autofocus />
            </div>
        </div>
    </div>
    <div class="flex justify-end items-center gap-3 h-fit mt-4 p-3 bg-primary-bg rounded-lg">
        <x-card-title :value="'Total'" />
        <x-price :value="$total" />
        @if ($no_of_people > 0 && $reserved_date != null)
            <div class="border-l-2 border-primary-fg px-1 py-5"></div>
            <x-button wire:click=reserve()>Reserve</x-button>
        @endif
    </div>

    {{-- Receipt Link --}}
    <a id="receipt-link" class="hidden" href="{{$receipt_path}}" target="_blank"></a>
</div>

<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/pikaday/css/pikaday.css">
<script src="https://cdn.jsdelivr.net/npm/pikaday/pikaday.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
<script>
    var datePicker = document.getElementById('reserved_date');
    let nextWeekTime = new Date().getTime() + 7 * 24 * 60 * 60 *1000;
    let disabledDates = [];

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
            datePicker.value = moment(date).format('Do MMMM YYYY');
            Livewire.emit('datePickerPicked', datePicker.value);
        }
    });

    document.addEventListener("DOMContentLoaded", () => {
        Livewire.hook('message.processed', (el, component) => {
            disabledDates = @this.disabledDates;
        })
    });
    
    window.addEventListener('reservation-created', event => {
        window.location.reload();
        alert(event.detail.accommodation + " has been successfully reserved.");
        document.getElementById('receipt-link').click();
    })
</script>