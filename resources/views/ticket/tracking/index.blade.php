<x-app-layout>
    <x-slot name="header">
        Tickets - Seguimiento Novedades
    </x-slot>

    <x-crud-index>
        <x-slot name="actions">
            
        </x-slot>

        <x-slot name="content">
            <livewire:table.ticket.traking-table/>
        </x-slot>
    </x-crud-index>
</x-app-layout>