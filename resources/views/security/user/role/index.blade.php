<x-app-layout>
    <x-slot name="header">
            Seguridad - Usuarios - Grupos
    </x-slot>

    <x-crud-index>
        <x-slot name="actions">
            <a href="{{ route('user.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-gray-700 hover:text-gray-500 focus:outline-none focus:border-blue-300 active:bg-gray-100 active:text-gray-800 transition duration-150 ease-in-out">
                <i class="fas fa-arrow-left mr-2"></i> Volver
            </a>
            <a href="{{ route('user.role.create', ['user' => $user]) }}" class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-blue-500 border border-transparent rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500" role="button"><i class="fa fa-plus">
                </i>&nbsp; Nuevo Registro
            </a>            
        </x-slot>

        <x-slot name="content">
            <livewire:table.security.user-role-table user="{{ $user->id }}"/>
        </x-slot>
    </x-crud-index>
</x-app-layout>