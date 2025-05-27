<x-app-layout>
    <x-slot name="header">
        Reporte - Eventos - Agenda de actividades
    </x-slot>

    <x-crud-index>
        <x-slot name="actions">
        </x-slot>

        <x-slot name="content">
            <livewire:table.report.timetable-event-table/>
        </x-slot>
    </x-crud-index>
</x-app-layout>