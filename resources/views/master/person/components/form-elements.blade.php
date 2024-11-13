<div class="text-gray-500">
    <div class="mt-4">
        {!! html()->label('Identificación del tercero')->for('identification') !!}
        {!! html()->text('identification', $person->identification ?? null)
            ->class('block mt-1 w-full' . ($errors->has('identification') ? ' is-invalid' : ''))
            ->placeholder('Identificación del tercero') !!}
    </div>
    <div class="mt-4">
        {!! html()->label('Nombre del tercero')->for('name') !!}
        {!! html()->text('name', $person->name ?? null)
            ->class('block mt-1 w-full' . ($errors->has('name') ? ' is-invalid' : ''))
            ->placeholder('Nombre del tercero') !!}
    </div>
    <div class="mt-4">
        {!! html()->label('Dirección del tercero (Opcional)')->for('address') !!}
        {!! html()->text('address', $person->address ?? null)
            ->class('block mt-1 w-full' . ($errors->has('address') ? ' is-invalid' : ''))
            ->placeholder('Dirección del tercero') !!}
    </div>
    <div class="mt-4">
        {!! html()->label('Teléfono del tercero (Opcional)')->for('phone') !!}
        {!! html()->text('phone', $person->phone ?? null)
            ->class('block mt-1 w-full' . ($errors->has('phone') ? ' is-invalid' : ''))
            ->placeholder('Teléfono del tercero') !!}
    </div>
    <div class="mt-4">
        {!! html()->label('Correo electrónico del tercero (Opcional)')->for('email') !!}
        {!! html()->email('email', $person->email ?? null)
            ->class('block mt-1 w-full' . ($errors->has('email') ? ' is-invalid' : ''))
            ->placeholder('Correo electrónico del tercero') !!}
    </div>
    <div class="mt-4">
        {!! html()->label('Whatsapp del tercero (Opcional)')->for('whatsapp') !!}
        {!! html()->text('whatsapp', $person->whatsapp ?? null)
            ->class('block mt-1 w-full' . ($errors->has('whatsapp') ? ' is-invalid' : ''))
            ->placeholder('Whatsapp del tercero') !!}
    </div>
    <div class="mt-4">
        {!! html()->label('Observaciones del tercero (Opcional)')->for('text') !!}
        {!! html()->textarea('text', $person->text ?? null)
            ->class('block mt-1 w-full' . ($errors->has('text') ? ' is-invalid' : ''))
            ->placeholder('Observaciones del tercero') !!}
    </div>
    <div class="mt-4">
        {!! html()->label('Fecha de nacimiento del tercero (Opcional)')->for('birth') !!}
        {!! html()->date('birth', $person->birth ?? null)
            ->class('block mt-1 w-full' . ($errors->has('birth') ? ' is-invalid' : ''))
            ->placeholder('Fecha de nacimiento del tercero') !!}
    </div>
    <div class="mt-4">
        {!! html()->label('Es Cliente?')->for('isClient') !!}
        {!! html()->checkbox('isClient', $person->isClient ?? null)
            ->class('block mt-1 w-full' . ($errors->has('isClient') ? ' is-invalid' : ''))
            ->placeholder('Es Cliente?') !!}
    </div>
    <div class="mt-4">
        {!! html()->label('Es Proveedor?')->for('isSupplier') !!}
        {!! html()->checkbox('isSupplier', $person->isSupplier ?? null)
            ->class('block mt-1 w-full' . ($errors->has('isSupplier') ? ' is-invalid' : ''))
            ->placeholder('Es Proveedor?') !!}
    </div>
    <div class="mt-4">
        {!! html()->label('Es Empleado?')->for('isEmployee') !!}
        {!! html()->checkbox('isEmployee', $person->isEmployee ?? null)
            ->class('block mt-1 w-full' . ($errors->has('isEmployee') ? ' is-invalid' : ''))
            ->placeholder('Es Empleado?') !!}
    </div>
    <div class="mt-4">
        {!! html()->label('Estado')->for('state') !!}
        {!! html()->checkbox('state', $person->state ?? null)
            ->class('block mt-1 w-full' . ($errors->has('state') ? ' is-invalid' : ''))
            ->placeholder('Estado') !!}
    </div>
</div>
