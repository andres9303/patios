<div class="text-gray-500">
    <div class="mt-4">
        {!! html()->label('Nombre del centro de costo')->for('name') !!}
        {!! html()->text('name', $company->name ?? null)
            ->class('block mt-1 w-full' . ($errors->has('name') ? ' is-invalid' : ''))
            ->placeholder('Nombre del centro de costos') !!}
    </div>
    <div class="mt-4">
        {!! html()->label('Dirección')->for('address') !!}
        {!! html()->text('address', $company->address ?? null)
            ->class('block mt-1 w-full' . ($errors->has('address') ? ' is-invalid' : ''))
            ->placeholder('Dirección del centro de costos') !!}
    </div>
    <div class="mt-4">
        {!! html()->label('Teléfono')->for('phone') !!}
        {!! html()->number('phone', $company->phone ?? null)
            ->class('block mt-1 w-full' . ($errors->has('phone') ? ' is-invalid' : ''))
            ->placeholder('Teléfono de contacto') !!}
    </div>
    <div class="mt-4">
        {!! html()->label('Correo electrónico')->for('email') !!}
        {!! html()->text('email', $company->email ?? null)
            ->class('block mt-1 w-full' . ($errors->has('email') ? ' is-invalid' : ''))
            ->placeholder('Correo electrónico del centro de costos') !!}
    </div>
    <div class="mt-4">
        {!! html()->label('Prefijo')->for('prefix') !!}
        {!! html()->text('prefix', $company->prefix ?? null)
            ->class('block mt-1 w-full' . ($errors->has('prefix') ? ' is-invalid' : ''))
            ->placeholder('Prefijo de las facturas') !!}
    </div>
    <div class="mt-4">
        {!! html()->label('Encabezado 1')->for('head1') !!}
        {!! html()->text('head1', $company->head1 ?? null)
            ->class('block mt-1 w-full' . ($errors->has('head1') ? ' is-invalid' : ''))
            ->placeholder('Encabezado 1 de las facturas') !!}
    </div>
    <div class="mt-4">
        {!! html()->label('Encabezado 2')->for('head2') !!}
        {!! html()->text('head2', $company->head2 ?? null)
            ->class('block mt-1 w-full' . ($errors->has('head2') ? ' is-invalid' : ''))
            ->placeholder('Encabezado 2 de las facturas') !!}
    </div>
    <div class="mt-4">
        {!! html()->label('Encabezado 3')->for('head3') !!}
        {!! html()->text('head3', $company->head3 ?? null)
            ->class('block mt-1 w-full' . ($errors->has('head3') ? ' is-invalid' : ''))
            ->placeholder('Encabezado 3 de las facturas') !!}
    </div>
    <div class="mt-4">
        {!! html()->label('Pie 1')->for('foot1') !!}
        {!! html()->text('foot1', $company->foot1 ?? null)
            ->class('block mt-1 w-full' . ($errors->has('foot1') ? ' is-invalid' : ''))
            ->placeholder('Pie 1 de las facturas') !!}
    </div>
    <div class="mt-4">
        {!! html()->label('Pie 2')->for('foot2') !!}
        {!! html()->text('foot2', $company->foot2 ?? null)
            ->class('block mt-1 w-full' . ($errors->has('foot2') ? ' is-invalid' : ''))
            ->placeholder('Pie 2 de las facturas') !!}
    </div>
    <div class="mt-4">
        {!! html()->label('Pie 3')->for('foot3') !!}
        {!! html()->text('foot3', $company->foot3 ?? null)
            ->class('block mt-1 w-full' . ($errors->has('foot3') ? ' is-invalid' : ''))
            ->placeholder('Pie 3 de las facturas') !!}
    </div>
    <div class="mt-4">
        {!! html()->label('Estado')->for('state') !!}
        {!! html()->checkbox('state', $company->state ?? false) !!}
    </div>
</div>
