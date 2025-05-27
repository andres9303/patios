<x-app-layout>
    <x-slot name="header">
        Reporte - Proyectos - Avances de responsable
    </x-slot>

    <x-crud-index>
        <x-slot name="actions">
        </x-slot>

        <x-slot name="content">
            <livewire:table.report.responsible-activity-table/>
        </x-slot>
    </x-crud-index>
</x-app-layout>