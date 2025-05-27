<div class="text-gray-500">
    <div class="mt-4">
        {!! html()->label('Tipo de Campo', 'type_field_id') !!}
        {!! html()->select('type_field_id', $typeFields->pluck('name', 'id'), isset($field) ? $field->type_field_id : null)
            ->class('block mt-1 w-full' . ($errors->has('type_field_id') ? ' is-invalid' : ''))
            ->placeholder('Seleccione un Tipo de Campo') !!}
    </div>

    <div class="mt-4">
        {!! html()->label('Nombre', 'name') !!}
        {!! html()->text('name', isset($field) ? $field->name : null)
            ->class('block mt-1 w-full' . ($errors->has('name') ? ' is-invalid' : ''))
            ->placeholder('Nombre del Campo') !!}
    </div>

    <div class="mt-4">
        {!! html()->label('Descripción', 'description') !!}
        {!! html()->textarea('description', isset($field) ? $field->description : null)
            ->class('block mt-1 w-full' . ($errors->has('description') ? ' is-invalid' : ''))
            ->placeholder('Descripción del Campo') !!}
    </div>

    <div class="mt-4">
        {!! html()->label('Agregar campo Descripción?', 'is_description') !!}
        {!! html()->checkbox('is_description', isset($field) ? $field->is_description : null)
            ->class('block mt-1 w-full' . ($errors->has('is_description') ? ' is-invalid' : ''))
            ->placeholder('Agregar campo Descripción?') !!}
    </div>

    <div class="mt-4">
        {!! html()->label('Campo Requerido?', 'is_required') !!}
        {!! html()->checkbox('is_required', isset($field) ? $field->is_required : null)
            ->class('block mt-1 w-full' . ($errors->has('is_required') ? ' is-invalid' : ''))
            ->placeholder('Campo Requerido?') !!}
    </div>

    <div class="mt-4">
        {!! html()->label('Estado', 'state') !!}
        {!! html()->checkbox('state', isset($field) ? $field->state : null)
            ->class('block mt-1 w-full' . ($errors->has('state') ? ' is-invalid' : ''))
            ->placeholder('Estado del Campo') !!}
    </div>

    <div class="mt-4">
        {!! html()->label('Orden', 'order') !!}
        {!! html()->number('order', isset($field) ? $field->order : null)
            ->class('block mt-1 w-full' . ($errors->has('order') ? ' is-invalid' : ''))
            ->placeholder('Orden del Campo') !!}
    </div>
</div>