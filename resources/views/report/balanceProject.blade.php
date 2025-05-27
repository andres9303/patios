<x-app-layout>
    <x-slot name="header">
        Reporte - Proyectos - Saldos de Proyectos
    </x-slot>

    <x-crud-index>
        <x-slot name="actions">
        </x-slot>

        <x-slot name="content">
            <livewire:table.report.balance-project-table/>
        </x-slot>
    </x-crud-index>
</x-app-layout>