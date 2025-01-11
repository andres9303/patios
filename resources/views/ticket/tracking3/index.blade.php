<x-app-layout>
    <x-slot name="header">
        Tickets - Seguimiento Objetos perdidos
    </x-slot>

    <x-crud-index>
        <x-slot name="actions">
            
        </x-slot>

        <x-slot name="content">
            <livewire:table.ticket.traking3-table/>
        </x-slot>
    </x-crud-index>
</x-app-layout>