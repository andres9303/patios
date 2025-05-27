<x-app-layout>
    <x-slot name="header">
        Reporte - Inventarios - Kardex
    </x-slot>

    <x-crud-index>
        <x-slot name="actions">
        </x-slot>

        <x-slot name="content">
            {!! html()->form('GET', route('report-kardex.index'))->open() !!}
                <!--filtros-->
                <div class="bg-gray-100 p-4 rounded-md shadow flex flex-col max-w-screen-xl mb-6">
                    <h3 class="text-xl font-semibold text-gray-800">Filtrar fechas</h3>
                    <div class="text-gray-500 "> 
                        <div class="mt-4">
                            {!! html()->label('Fecha de Inicio', 'start_date') !!}
                            {!! html()->date('start_date', isset($start_date) ? $start_date : null)
                                ->class('block mt-1 w-full' . ($errors->has('start_date') ? ' is-invalid' : '')) !!}
                        </div>
                        
                        <div class="mt-4">
                            {!! html()->label('Fecha de Fin', 'end_date') !!}
                            {!! html()->date('end_date', isset($end_date) ? $end_date : null)
                                ->class('block mt-1 w-full' . ($errors->has('end_date') ? ' is-invalid' : '')) !!}
                        </div>

                        <div class="mt-4">
                            {!! html()->button('<i class="fa fa-save"></i> Buscar', 'submit')
                                ->class('bg-blue-500 block mt-1 w-full hover:bg-blue-600 text-white py-2 px-4')
                            !!}
                        </div>
                    </div>
                </div>
                
                <livewire:table.report.kardex-table :start_date="$start_date" :end_date="$end_date"/>                
            {!! html()->form()->close() !!}
        </x-slot>
    </x-crud-index>
</x-app-layout>