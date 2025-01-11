<x-app-layout>
    <x-slot name="header">        
        Inicio
    </x-slot>

    <div class="py-12">
        <!-- Accesos directos -->
        <div class="p-6 sm:px-20 bg-white border-b border-gray-200">
            @if ($shortcuts->count() > 0)
            <h1 class="mb-5 text-gray-400 font-bold">Accesos directos</h1>
            <div class="grid grid-cols-1 gap-4 xl:grid-cols-4 md:grid-cols-3 sm:grid-cols-2"> 
                @foreach ($shortcuts as $menu)
                <a href="{{ route($menu->route.'.index') }}" class="flex items-center text-white hover:text-indigo-900 p-4 bg-indigo-900 shadow rounded-lg transition-all hover:bg-indigo-500">
                    <div class="flex items-center justify-center w-10 h-10 mr-4 bg-indigo-500 rounded-md">
                        <i class="text-white {{ $menu->icon }}"></i>
                    </div>
                    <div class="text-lg font-medium">{{ $menu->name }}</div>
                </a> 
                @endforeach                   
            </div>
            @endif            
        </div>

        <!-- Dashboard -->
        <div class="p-6 sm:px-20 bg-white border-b border-gray-200">
            <h1 class="mb-5 text-gray-400 font-bold">Dashboard</h1>
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">     
                <!-- Pendientes novedades -->
                @can('view-menu',"resolve-ticket")   
                <a href="{{ route('resolve-ticket.index') }}">
                <div class="flex items-center p-4 bg-indigo-900 rounded-lg shadow-md">                    
                    <div class="flex-shrink-0 mr-4">
                        <span class="text-3xl text-indigo-200">
                            <i class="fas fa-file-signature"></i>
                        </span>
                    </div>
                    <div>
                        <p class="text-lg font-semibold text-gray-50">Novedades</p>
                        <p class="text-2xl font-bold text-indigo-500">{{ $resolve_tickets }}</p>
                    </div>
                    <div class="ml-auto text-indigo-500">
                        <i class="fas fa-arrow-right"></i>
                    </div>
                </div>   
                </a>             
                @endcan

                <!-- Pendientes Informativos -->
                @can('view-menu',"resolve-2ticket")   
                <a href="{{ route('resolve-2ticket.index') }}">
                <div class="flex items-center p-4 bg-indigo-900 rounded-lg shadow-md">                    
                    <div class="flex-shrink-0 mr-4">
                        <span class="text-3xl text-indigo-200">
                            <i class="fas fa-info"></i>
                        </span>
                    </div>
                    <div>
                        <p class="text-lg font-semibold text-gray-50">Tickets Informativos</p>
                        <p class="text-2xl font-bold text-indigo-500">{{ $resolve2_tickets }}</p>
                    </div>
                    <div class="ml-auto text-indigo-500">
                        <i class="fas fa-arrow-right"></i>
                    </div>
                </div>   
                </a>             
                @endcan

                <!-- Pendientes Objetos perdidos -->
                @can('view-menu',"resolve-3ticket")   
                <a href="{{ route('resolve-3ticket.index') }}">
                <div class="flex items-center p-4 bg-indigo-900 rounded-lg shadow-md">                    
                    <div class="flex-shrink-0 mr-4">
                        <span class="text-3xl text-indigo-200">
                            <i class="fas fa-search"></i>
                        </span>
                    </div>
                    <div>
                        <p class="text-lg font-semibold text-gray-50">Objetos Perdidos</p>
                        <p class="text-2xl font-bold text-indigo-500">{{ $resolve3_tickets }}</p>
                    </div>
                    <div class="ml-auto text-indigo-500">
                        <i class="fas fa-arrow-right"></i>
                    </div>
                </div>   
                </a>             
                @endcan
            </div>       
        </div>
    </div>
</x-app-layout>
