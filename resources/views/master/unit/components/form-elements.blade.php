<div class="text-gray-500">    
    <div class="mt-4">
        {!! html()->label('Nombre de la unidad', 'name') !!}
        {!! html()->text('name', isset($unit) ? $unit->name : null)
            ->class('block mt-1 w-full' . ($errors->has('name') ? ' is-invalid' : ''))
            ->placeholder('Nombre de la unidad') !!}
    </div>
    
    <div class="mt-4">
        {!! html()->label('Unidad', 'unit') !!}
        {!! html()->number('unit', isset($unit) ? $unit->unit : null)
            ->class('block mt-1 w-full' . ($errors->has('unit') ? ' is-invalid' : ''))
            ->placeholder('Unidad')
            ->attribute('step', '0.000001') !!}
    </div>
    
    <div class="mt-4">
        {!! html()->label('Tiempo', 'time') !!}
        {!! html()->number('time', isset($unit) ? $unit->time : null)
            ->class('block mt-1 w-full' . ($errors->has('time') ? ' is-invalid' : ''))
            ->placeholder('Tiempo')
            ->attribute('step', '0.000001') !!}
    </div>
    
    <div class="mt-4">
        {!! html()->label('Masa', 'mass') !!}
        {!! html()->number('mass', isset($unit) ? $unit->mass : null)
            ->class('block mt-1 w-full' . ($errors->has('mass') ? ' is-invalid' : ''))
            ->placeholder('Masa')
            ->attribute('step', '0.000001') !!}
    </div>
    
    <div class="mt-4">
        {!! html()->label('Longitud', 'longitude') !!}
        {!! html()->number('longitude', isset($unit) ? $unit->longitude : null)
            ->class('block mt-1 w-full' . ($errors->has('longitude') ? ' is-invalid' : ''))
            ->placeholder('Longitud')
            ->attribute('step', '0.000001') !!}
    </div>
    
    <div class="mt-4">
        {!! html()->label('Estado')->for('state') !!}
        {!! html()->checkbox('state', $unit->state ?? 1)
            ->class('block mt-1 w-full' . ($errors->has('state') ? ' is-invalid' : ''))!!}
    </div>
    
    <div class="mt-4">
        {!! html()->label('Unidad Base', 'unit_id') !!}
        {!! html()->select('unit_id', $units->pluck('name', 'id'), isset($unit) ? $unit->unit_id : null)
            ->class('block mt-1 w-full')
            ->placeholder('Unidad Padre') !!}
    </div>
    
    <div class="mt-4">
        {!! html()->label('Factor', 'factor') !!}
        {!! html()->number('factor', isset($unit) ? $unit->factor : null)
            ->class('block mt-1 w-full' . ($errors->has('factor') ? ' is-invalid' : ''))
            ->placeholder('Factor')
            ->attribute('step', '0.000001') !!}
    </div>
</div>