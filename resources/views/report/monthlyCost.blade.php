<x-app-layout>
    <x-slot name="header">
        Reporte - Costos - Costos Mensuales
    </x-slot>

    <x-crud-index>
        <x-slot name="actions">
        </x-slot>

        <x-slot name="content">
            <livewire:table.report.monthly-cost-table/>
        </x-slot>
    </x-crud-index>
</x-app-layout>