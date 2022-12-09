@props(['packages', 'accommodation_id'])

<div {{ $attributes->merge(['class' => 'font-semibold text-sm rounded-lg m-3 pt-0.5']) }}>
    @foreach ($packages as $package)
    <table class="inline-block p-3 mr-4 border-2 h-auto rounded-lg border-primary-fg transition duration-700 ease-in-out hover:scale-105">
        <thead>
            <tr>
                <th colspan="2">{{ $package->name }}</th>
            </tr>
        </thead>
        <tbody class="text-left">
            <tr class="text-center">
                <td colspan="2">{{ date('h:i A', strtotime($package->start_time)) }}- {{ date('h:i A', strtotime($package->end_time)) }}</td>
            </tr>
            <tr>
                <th>Rate</th>
                <td>&#8369; {{ number_format(App\Facades\Format::moneyForDisplay($package->pivot->rate), 2) }}</td>
            </tr>
            <tr>
                <th>Max</th>
                <td>{{ $package->pivot->max_people }} people</td>
            </tr>
            <tr>
                <td colspan="2">
                    <form>
                        <x-button formaction="{{route('reservations', [$accommodation_id, $package->id])}}">Reserve Now</x-button>
                    </form>
                </td>
            </tr>
        </tbody>
    </table>
    @endforeach
</div>
