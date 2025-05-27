<x-app-layout>
    <x-slot name="header">
        Reporte - Proyectos - Actividades pendientes por usuario
    </x-slot>

    <x-crud-index>
        <x-slot name="actions">
        </x-slot>

        <x-slot name="content">
            <livewire:table.report.pending-activity-table/>
        </x-slot>
    </x-crud-index>
</x-app-layout>