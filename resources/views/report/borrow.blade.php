<x-app-layout>
    <x-slot name="header">
        Reporte - Costos - Inventario en Prestamos
    </x-slot>

    <x-crud-index>
        <x-slot name="actions">
        </x-slot>

        <x-slot name="content">
            {!! html()->form('GET', route('report-borrow.index'))->open() !!}
                <!--filtros-->
                <div class="bg-gray-100 p-4 rounded-md shadow flex flex-col max-w-screen-xl mb-6">
                    <h3 class="text-xl font-semibold text-gray-800">Filtrar fechas</h3>
                    <div class="text-gray-500 "> 
                        <div class="mt-4">
                            {!! html()->label('Fecha de corte', 'date') !!}
                            {!! html()->date('date', isset($date) ? $date : null)
                                ->class('block mt-1 w-full' . ($errors->has('date') ? ' is-invalid' : '')) !!}
                        </div>

                        <div class="mt-4">
                            {!! html()->button('<i class="fa fa-save"></i> Buscar', 'submit')
                                ->class('bg-blue-500 block mt-1 w-full hover:bg-blue-600 text-white py-2 px-4')
                            !!}
                        </div>
                    </div>
                </div>

                <livewire:table.report.borrow-table :date="$date"/>
            {!! html()->form()->close() !!}
        </x-slot>
    </x-crud-index>
</x-app-layout>