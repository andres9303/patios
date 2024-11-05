<x-app-layout>
    <x-slot name="header">
            Seguridad - Usuarios
    </x-slot>

    <x-crud-index>
        <x-slot name="actions">
            <a class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-blue-500 border border-transparent rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500" href="{{ route('user.create') }}" role="button"><i class="fa fa-plus"></i>&nbsp; Nuevo Registro</a>
        </x-slot>

        <x-slot name="content">
            <livewire:table.security.user-table/>
        </x-slot>
    </x-crud-index>
</x-app-layout>