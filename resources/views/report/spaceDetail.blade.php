<x-app-layout>
    <x-slot name="header">
        Reporte - Espacios - Detalle de Espacios
    </x-slot>

    <x-crud-index>
        <x-slot name="actions">
        </x-slot>

        <x-slot name="content">
            <livewire:table.report.space-detail-table/>
        </x-slot>
    </x-crud-index>
</x-app-layout>