<x-app-layout>
    <x-slot name="header">
        Reporte - Inventarios - Movimientos de Inventario
    </x-slot>

    <x-crud-index>
        <x-slot name="actions">
        </x-slot>

        <x-slot name="content">
            <livewire:table.report.movement-inv-table/>
        </x-slot>
    </x-crud-index>
</x-app-layout>