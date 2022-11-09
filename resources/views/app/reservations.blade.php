<x-app-layout>
    <div class="flex ml-10">
        <div class="w-1/4 h-fit">
            <div class="h-full m-5 p-3 bg-primary-bg rounded-lg">
                <x-card-title :value="'User Information'" />
                <div class="w-full sm:max-w-md overflow-hidden sm:rounded-lg">
                    <!-- Validation Errors -->
                    <x-auth-validation-errors class="mb-4" :errors="$errors" />

                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <!-- First Name -->
                        <div class="mt-4">
                            <x-label for="first_name" :value="__('First Name')" />

                            <x-input id="first_name" :disabled="auth()->user() != null" class="px-1.5 py-1 block mt-1 w-full" type="text" name="first_name" :value="auth()->user()?->first_name" required autofocus />
                        </div>

                        <!-- Last Name -->
                        <div class="mt-4">
                            <x-label for="last_name" :value="__('Last Name')" />

                            <x-input id="last_name" :disabled="auth()->user() != null" class="px-1.5 py-1 block mt-1 w-full" type="text" name="last_name" :value="auth()->user()?->last_name" required />
                        </div>

                        <!-- Email Address -->
                        <div class="mt-4">
                            <x-label for="email" :value="__('Email')" />

                            <x-input id="email" :disabled="auth()->user() != null" class="px-1.5 py-1 block mt-1 w-full" type="email" name="email" :value="auth()->user()?->email" required />
                        </div>

                        <!-- Contact Number -->
                        <div class="mt-4">
                            <x-label for="contact_number" :value="__('Contact Number')" />

                            <x-input id="contact_number" :disabled="auth()->user() != null" class="px-1.5 py-1 block mt-1 w-full" type="tel" pattern="(\+63|0)9[0-9]{9}" name="contact_number" :value="auth()->user()?->contact_number" required />
                        </div>

                        <!-- Password -->
                        <x-input id="password" class="hidden"
                                        :value="'password'"
                                        type="password"
                                        name="password"
                                        required autocomplete="new-password" />

                        <!-- Confirm Password -->
                        <x-input id="password_confirmation" class="hidden"
                                        :value="'password'"
                                        type="password"
                                        name="password_confirmation" required />

                        @guest
                            <div class="flex items-center justify-end mt-4">
                                <x-button class="ml-4">
                                    {{ __('Save') }}
                                </x-button>
                            </div>
                        @endguest
                    </form>
                </div>
            </div>
        </div>
        <div class="w-2/3 m-5">
            @auth
                @if ($current_reservation)
                    <livewire:reservations-info :current_reservation="$current_reservation" />
                @else
                    <livewire:reservation-process :accommodation_id="$accommodation_id" :package_id="$package_id" />
                @endif
            @endauth
            @guest
                <x-card-title :value="'Fill out the user information first.'" />
            @endguest
        </div>
    </div>
</x-app-layout>