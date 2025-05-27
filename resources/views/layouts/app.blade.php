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
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.9/dist/chart.umd.min.js"></script>

        <!-- Styles -->
        @livewireStyles
    </head>
    <body class="font-sans antialiased mr-1">
        <span class="bg-green-500"></span>
        <span class="bg-green-600"></span>
        <div class="flex h-screen bg-gray-100">
            <!-- Contenido principal -->
            <div class="w-full h-full overflow-auto">
                <!-- Barra superior -->
                <header class="bg-indigo-900 text-gray-100 shadow">
                    <div class="px-4 py-4"> 
                        @if (Auth::user()->current_company_id)
                        <i class="fas fa-building"></i> {{ Auth::user()->currentCompany->name }} | 
                        @endif
                        <i class="fas fa-link"></i>  {{ $header }}
                        @if (Auth::user()->username)
                        | <i class="fas fa-user"></i> {{ Auth::user()->username }}
                        @endif
                    </div>
                </header>
        
                <!-- Contenido -->
                <main class="flex-1 overflow-y-auto p-4">
                    <div class="bg-white overflow-hidden shadow rounded-lg mr-6">
                        {{ $slot }}
                    </div>
                </main>
            </div>
        
            @livewire('navigation-menu')
        </div>

        @stack('modals')

        @livewireScripts
        
        @stack('scripts')
    </body>
</html>
