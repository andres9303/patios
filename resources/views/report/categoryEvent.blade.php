<x-app-layout>
    <x-slot name="header">
        Reporte - Eventos - Eventos por Categoría
    </x-slot>

    <x-crud-index>
        <x-slot name="actions">
        </x-slot>

        <x-slot name="content">
            <livewire:table.report.category-event-table/>
        </x-slot>
    </x-crud-index>
</x-app-layout>