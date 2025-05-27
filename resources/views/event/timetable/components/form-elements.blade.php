<div class="text-gray-500"> 
    <div class="mt-4">
        {!! html()->label('Fecha', 'date') !!}
        {!! html()->date('date', isset($timetable) ? $timetable->date : null)
            ->class('block mt-1 w-full' . ($errors->has('date') ? ' is-invalid' : ''))
            ->placeholder('Fecha') !!}
    </div>

    <div class="mt-4">
        {!! html()->label('Actividad', 'item_id') !!}
        {!! html()->select('item_id', $activities->pluck('name', 'id'), isset($timetable) ? $timetable->item_id : null)
            ->class('block mt-1 w-full' . ($errors->has('item_id') ? ' is-invalid' : ''))
            ->placeholder('Actividad') !!}
    </div>

    <div class="mt-4">
        {!! html()->label('Responsable', 'person_id') !!}
        {!! html()->select('person_id', $persons->pluck('name', 'id'), isset($timetable) ? $timetable->person_id : null)
            ->class('block mt-1 w-full' . ($errors->has('person_id') ? ' is-invalid' : ''))
            ->placeholder('Operador Responsable') !!}
    </div>

    <div class="mt-4">
        {!! html()->label('% Ocupación', 'percentage') !!}
        {!! html()->number('percentage', isset($timetable) ? $timetable->percentage : null)
            ->class('block mt-1 w-full' . ($errors->has('percentage') ? ' is-invalid' : ''))
            ->placeholder('Porcentaje Ocupación') !!}
    </div>

    <div class="mt-4">
        {!! html()->label('Cantidad', 'cant') !!}
        {!! html()->number('cant', isset($timetable) ? $timetable->cant : null)
            ->class('block mt-1 w-full' . ($errors->has('cant') ? ' is-invalid' : ''))
            ->placeholder('Cantidad') !!}
    </div>

    <div class="mt-4">
        {!! html()->label('Descripción', 'text') !!}
        {!! html()->textarea('text', isset($timetable) ? $timetable->text : null)
            ->class('block mt-1 w-full' . ($errors->has('text') ? ' is-invalid' : ''))
            ->placeholder('Descripción') !!}
    </div>
</div>