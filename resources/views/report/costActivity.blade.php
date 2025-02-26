<x-app-layout>
    <x-slot name="header">
        Reporte - Costos - Costos por Actividad
    </x-slot>

    <x-crud-index>
        <x-slot name="actions">
        </x-slot>

        <x-slot name="content">
            <livewire:table.report.cost-activity-table/>
        </x-slot>
    </x-crud-index>
</x-app-layout>