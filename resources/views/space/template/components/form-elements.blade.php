<div class="text-gray-500">    
    <div class="mt-4">
        {!! html()->label('Espacio', 'space_id') !!}
        {!! html()->select('space_id', $spaces->pluck('name', 'id'), isset($template) ? $template->space_id : null)
            ->class('block mt-1 w-full' . ($errors->has('space_id') ? ' is-invalid' : ''))
            ->placeholder('Seleccione un Espacio') !!}
    </div>

    <div class="mt-4">
        {!! html()->label('Nombre', 'name') !!}
        {!! html()->text('name', isset($template) ? $template->name : null)
            ->class('block mt-1 w-full' . ($errors->has('name') ? ' is-invalid' : ''))
            ->placeholder('Nombre de la Plantilla') !!}
    </div>

    <div class="mt-4">
        {!! html()->label('Descripción', 'description') !!}
        {!! html()->textarea('description', isset($template) ? $template->description : null)
            ->class('block mt-1 w-full' . ($errors->has('description') ? ' is-invalid' : ''))
            ->placeholder('Descripción de la Plantilla') !!}
    </div>

    <div class="mt-4">
        {!! html()->label('Estado', 'state') !!}
        {!! html()->checkbox('state', isset($template) ? $template->state : null)
            ->class('block mt-1 w-full' . ($errors->has('state') ? ' is-invalid' : ''))
            ->placeholder('Estado de la Plantilla') !!}
    </div>    
</div>