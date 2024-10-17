<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">


        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Styles -->
        @livewireStyles
    </head>
    <body class="font-sans antialiased">
        <div class="flex h-screen bg-gray-100">
            <!-- Contenido principal -->
            <div class="flex flex-col flex-1">
                <!-- Barra superior -->
                <header class="bg-white shadow">
                    <div class="px-4 py-4">
                        <h1 class="text-lg font-semibold text-gray-800">{{ $header }}</h1>
                    </div>
                </header>
        
                <!-- Contenido -->
                <main class="flex-1 overflow-y-auto p-6 mr-4">
                    <div class="bg-white shadow rounded-lg p-6">
                        {{ $slot }}
                    </div>
                </main>
            </div>
        
            @livewire('navigation-menu')
        </div>

        @stack('modals')

        @livewireScripts
    </body>
</html>
