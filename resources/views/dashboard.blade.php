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
                <a href="{{ route($menu->route.'.index') }}" class="flex items-center text-indigo-500 hover:text-indigo-900 p-4 bg-indigo-900 shadow rounded-lg transition-all hover:bg-indigo-500">
                    <div class="flex items-center justify-center w-10 h-10 mr-4 bg-indigo-500 rounded-md">
                        <i class="text-indigo-100 {{ $menu->icon }}"></i>
                    </div>
                    <div class="text-lg font-medium">{{ $menu->name }}</div>
                </a> 
                @endforeach                   
            </div>
            @endif            
        </div>


    </div>
</x-app-layout>
