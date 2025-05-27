<div>
    <div class="mt-4 text-gray-500">
        {!! Html::label('Compañías', 'Empresas Asociadas')->for('companies') !!}
        {!! Html::select('companies[]', collect($companies)->pluck('name', 'id')->toArray(), null)
                ->attribute('wire:model', 'selectedCompanies')
                ->attribute('id', 'companies')
                ->multiple()
                ->class('mt-2 block w-full border border-gray-300 rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-indigo-500') !!}
        {!! Html::label('*Usa Ctrl para seleccionar multiples compañías', 'Empresas Asociadas')->for('companies')->class(' text-sm') !!}
    </div>
</div>
