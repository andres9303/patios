<x-app-layout>
    <x-slot name="header">
        Reporte - Tickets - Historial de Tickets
    </x-slot>

    <x-crud-index>
        <x-slot name="actions">
        </x-slot>

        <x-slot name="content">
            <livewire:table.report.ticket-history-table/>
        </x-slot>
    </x-crud-index>
</x-app-layout>