<!-- Menú lateral derecho -->
<div x-data="{ open: false }" class="relative flex-shrink-0">
    <!-- Barra de control para abrir/cerrar el menú (siempre visible) -->
    <div class="fixed inset-y-0 right-0 bg-indigo-900 text-white flex items-center justify-center w-8 cursor-pointer z-50" @click="open = !open">
        <!-- Icono de hamburguesa (abrir menú) -->
        <svg x-show="!open" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
        </svg>
        <!-- Icono de "X" (cerrar menú) -->
        <svg x-show="open" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
        </svg>
    </div>

    <!-- Menú lateral derecho -->
    <div :class="open ? 'translate-x-0' : 'translate-x-full'" class="fixed inset-y-0 right-0 w-64 bg-indigo-800 text-white transform transition-transform duration-300 z-40">
        <div class="flex flex-col h-full">
            <!-- Imagen del usuario -->
            <div class="flex items-center justify-center h-24 bg-indigo-900">
                <img class="h-20 w-20 rounded-full object-cover" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
            </div>

            <!-- Menú de navegación -->
            <nav class="flex-1 px-2 py-4">
                <x-nav-link href="{{ route('home') }}" :active="request()->routeIs('home')" icon="fas fa-home">
                    {{ __('Home') }}
                </x-nav-link>
                @php
                    $control = 0;
                @endphp

                @foreach (Auth::user()->menu as $m)
                    @if ($m->route)
                    <!-- Submenú -->
                    <div x-show="open" class="pl-8">
                        <x-nav-link href="#" :active="request()->routeIs($m->active)" icon="{{ $m->icon }}">
                            {{ $m->name }}
                        </x-nav-link>
                    </div>
                    @else
                        @if ($control > 0)
                    </div>
                        @endif
                    <!-- Menú -->
                    <div x-data="{ open: false }">
                        <a href="#" @click="open = !open" class="flex justify-between items-center px-4 py-2 text-sm font-medium hover:bg-indigo-500 rounded-md">
                            <i class="{{ $m->icon }} mr-2"></i>
                            {{ $m->name }}
                            <svg :class="{ 'rotate-180': open }" class="w-4 h-4 mr-3 transition-transform transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </a>

                        @php
                            $control++;
                        @endphp
                    @endif
                @endforeach
            </nav>

            <!-- Botón de cerrar sesión (fijo al final) -->
            <div class="p-4 bg-indigo-900">
                <form method="POST" action="{{ route('logout') }}" x-data class="flex items-center justify-center px-4 py-2 mr-4 ">
                    @csrf
                    <x-nav-link href="{{ route('logout') }}" @click.prevent="$root.submit();" icon="fas fa-sign-out-alt" class="text-sm justify-center font-medium bg-red-500 hover:bg-red-600 text-white hover:text-white rounded-md w-full h-full">
                        {{ __('Log Out') }}
                    </x-nav-link>
                </form>
            </div>
        </div>
    </div>
</div>
