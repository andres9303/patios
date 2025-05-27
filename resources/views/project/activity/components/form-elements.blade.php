<div class="text-gray-500">    
    <div class="mt-4">
        {!! html()->label('Código', 'code') !!}
        {!! html()->text('code', isset($activity) ? $activity->code : null)
            ->class('block mt-1 w-full' . ($errors->has('code') ? ' is-invalid' : ''))
            ->placeholder('Código de la actividad') !!}
    </div>
    
    <div class="mt-4">
        {!! html()->label('Nombre', 'name') !!}
        {!! html()->text('name', isset($activity) ? $activity->name : null)
            ->class('block mt-1 w-full' . ($errors->has('name') ? ' is-invalid' : ''))
            ->placeholder('Nombre de la actividad') !!}
    </div>

    <div class="mt-4">
        {!! html()->label('Fecha de Inicio', 'start_date') !!}
        {!! html()->date('start_date', isset($activity) ? $activity->start_date : null)
            ->class('block mt-1 w-full' . ($errors->has('start_date') ? ' is-invalid' : '')) !!}
    </div>
    
    <div class="mt-4">
        {!! html()->label('Fecha de Fin', 'end_date') !!}
        {!! html()->date('end_date', isset($activity) ? $activity->end_date : null)
            ->class('block mt-1 w-full' . ($errors->has('end_date') ? ' is-invalid' : '')) !!}
    </div>
    
    <div class="mt-4">
        {!! html()->label('Unidad', 'unit_id') !!}
        {!! html()->select('unit_id', $units->pluck('name', 'id'), isset($activity) ? $activity->unit_id : null)
            ->class('block mt-1 w-full' . ($errors->has('unit_id') ? ' is-invalid' : ''))
            ->placeholder('Seleccione una unidad') !!}
    </div>

    <div class="mt-4">
        {!! html()->label('Cantidad', 'cant') !!}
        {!! html()->number('cant', isset($activity) ? $activity->cant : null)
            ->class('block mt-1 w-full' . ($errors->has('cant') ? ' is-invalid' : ''))
            ->placeholder('Cantidad')
            ->attribute('step', '0.0001') !!}
    </div>
    
    <div class="mt-4">
        {!! html()->label('Valor unitario estimado', 'value') !!}
        {!! html()->number('value', isset($activity) ? $activity->value : null)
            ->class('block mt-1 w-full' . ($errors->has('value') ? ' is-invalid' : ''))
            ->placeholder('Valor')
            ->attribute('step', '0.0001') !!}
    </div>

    <div class="mt-4">
        {!! html()->label('Costo unitario estimado', 'cost') !!}
        {!! html()->number('cost', isset($activity) ? $activity->cost : null)
            ->class('block mt-1 w-full' . ($errors->has('cost') ? ' is-invalid' : ''))
            ->placeholder('Valor')
            ->attribute('step', '0.0001') !!}
    </div>
    
    <div class="mt-4">
        {!! html()->label('Descripción', 'text') !!}
        {!! html()->textarea('text', isset($activity) ? $activity->text : null)
            ->class('block mt-1 w-full' . ($errors->has('text') ? ' is-invalid' : ''))
            ->placeholder('Descripción de la actividad') !!}
    </div>
    
    <div class="mt-4">
        {!! html()->label('Estado', 'state') !!}
        {!! html()->checkbox('state', isset($activity) ? $activity->state : false)
            ->class('block mt-1 w-full' . ($errors->has('state') ? ' is-invalid' : '')) !!}
    </div>

    <div class="mt-4">
        {!! html()->label('Responsable', 'user_id') !!}
        {!! html()->select('user_id', $users->pluck('name', 'id'), isset($activity) ? $activity->user_id : null)
            ->class('block mt-1 w-full' . ($errors->has('user_id') ? ' is-invalid' : ''))
            ->placeholder('Seleccione un responsable') !!}
    </div>
</div>