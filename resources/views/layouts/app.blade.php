<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Tic Tac Toe</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        @livewireStyles
    </head>
    <body class="antialiased">
        @include('layouts.navigation')
        <div class="min-h-screen bg-gray-900 bg-center sm:flex sm:justify-center sm:items-center bg-dots-darker selection:bg-red-500 selection:text-white">
            <div class="p-6 mx-auto max-w-7xl lg:p-8">
                {{ $slot }}
            </div>
        </div>

        @livewireScripts
    </body>
</html>
