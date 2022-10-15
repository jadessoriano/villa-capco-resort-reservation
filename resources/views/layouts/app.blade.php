<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">

        <meta name="application-name" content="{{ config('app.name') }}">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">
        @stack('styles')

        <!-- Livewire -->
        @livewireStyles
        @livewireScripts

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            <header>
                @include('layouts.navigation')
            </header>

            <main>
                {{ $slot }}
            </main>

            <footer class="text-center border-t-2 border-primary-fg text-sm mx-8 mt-4 py-4">
                © Villa Capco 2022.<br />
                Built using TALL stack (Tailwind CSS, Alpine JS, Laravel, Livewire).<br /><br />
                Need help? Contact us at <span class="text-blue-500">0917 546 7499</span><br />
                Or visit our facebook page <a class="text-blue-500" href="https://www.facebook.com/villacapco" target="_blank">https://www.facebook.com/villacapco</a><br />
                Have a visit at our resort at <span class="text-blue-500">55 Axis Road, Kalawaan, Pasig, Philippines</span><br />
            </footer>
        </div>

        @stack('scripts')
    </body>
</html>
