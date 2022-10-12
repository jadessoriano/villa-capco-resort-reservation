<x-app-layout>
    <div class="grid lg:grid-cols-3 md:grid-cols-2 grid-cols-1 justify-around m-10 gap-10">
        @foreach ($caterings as $catering)
            <article class="bg-primary-bg border rounded-lg transition duration-700 ease-in-out hover:scale-105 overflow-hidden">
                <div class="relative pb-2/3 rounded-lg">
                    <img src="{{ asset('storage/' . $catering->image_path) }}" alt="{{ $catering->name }}" class="absolute top-0 object-cover w-full h-full">
                </div>

                <div class="p-4">
                    <x-card-title :value="$catering->name" class="text-xl"/>
                    <x-price :value="$catering->rate" class="italic" />
                    <x-card-description :value="$catering->description" />
                </div>
            </article>
        @endforeach
    </div>
</x-app-layout>