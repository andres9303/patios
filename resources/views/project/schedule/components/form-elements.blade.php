<div class="text-gray-500">
    <div class="mt-4">
        {!! html()->label('Proyecto', 'project_id') !!}
        {!! html()->select('project_id', $projects->pluck('name', 'id'), isset($schedule) ? $schedule->project_id : null)
            ->class('block mt-1 w-full' . ($errors->has('project_id') ? ' is-invalid' : ''))
            ->placeholder('Seleccione un proyecto') !!}
    </div>

    <div class="mt-4">
        {!! html()->label('Espacio', 'space_id') !!}
        {!! html()->select('space_id', $spaces->pluck('name', 'id'), isset($schedule) ? $schedule->space_id : null)
            ->class('block mt-1 w-full' . ($errors->has('space_id') ? ' is-invalid' : ''))
            ->placeholder('Seleccione un espacio') !!}
    </div>

    <div class="mt-4">
        {!! html()->label('Fecha ultima programación', 'date') !!}
        {!! html()->date('date', isset($schedule) ? $schedule->date : null)
            ->class('block mt-1 w-full' . ($errors->has('date') ? ' is-invalid' : ''))
            ->placeholder('Seleccione una fecha') !!}
    </div>

    <div class="mt-4">
        {!! html()->label('Días para programar', 'days') !!}
        {!! html()->number('days', isset($schedule) ? $schedule->days : null)
            ->class('block mt-1 w-full' . ($errors->has('days') ? ' is-invalid' : ''))
            ->placeholder('Días') !!}
    </div>

    <div class="mt-4">
        {!! html()->label('Programaciones', 'cant') !!}
        {!! html()->number('cant', isset($schedule) ? $schedule->cant : null)
            ->class('block mt-1 w-full' . ($errors->has('cant') ? ' is-invalid' : ''))
            ->placeholder('Cantidad') !!}
    </div>

    <div class="mt-4">
        {!! html()->label('Descripción', 'text') !!}
        {!! html()->textarea('text', isset($schedule) ? $schedule->text : null)
            ->class('block mt-1 w-full' . ($errors->has('text') ? ' is-invalid' : ''))
            ->placeholder('Descripción') !!}
    </div>

    <div class="mt-4">
        {!! html()->label('Estado', 'state') !!}
        {!! html()->checkbox('state', isset($schedule) ? $schedule->state : false)
            ->class('block mt-1 w-full' . ($errors->has('state') ? ' is-invalid' : '')) !!}
    </div>
</div>  