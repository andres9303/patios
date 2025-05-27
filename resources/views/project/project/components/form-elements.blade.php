<div class="text-gray-500">   
    <div class="mt-4">
        {!! html()->label('Tipo', 'type') !!}
        {!! html()->select('type', [0 => 'Presupuesto', 1 => 'Proyecto'], isset($project) ? $project->type : null)
            ->class('block mt-1 w-full' . ($errors->has('type') ? ' is-invalid' : ''))
            ->placeholder('Seleccione un tipo') !!}
    </div>

    <div class="mt-4">
        {!! html()->label('Nombre', 'name') !!}
        {!! html()->text('name', isset($project) ? $project->name : null)
            ->class('block mt-1 w-full' . ($errors->has('name') ? ' is-invalid' : ''))
            ->placeholder('Nombre del proyecto') !!}
    </div>
    
    <div class="mt-4">
        {!! html()->label('Descripción', 'text') !!}
        {!! html()->textarea('text', isset($project) ? $project->text : null)
            ->class('block mt-1 w-full' . ($errors->has('text') ? ' is-invalid' : ''))
            ->placeholder('Descripción del proyecto') !!}
    </div>
    
    <div class="mt-4">
        {!! html()->label('Estado', 'state') !!}
        {!! html()->checkbox('state', isset($project) ? $project->state : false)
            ->class('block mt-1 w-full' . ($errors->has('state') ? ' is-invalid' : '')) !!}
    </div>
    
    <div class="mt-4">
        {!! html()->label('Concepto', 'concept') !!}
        {!! html()->number('concept', isset($project) ? $project->concept : null)
            ->class('block mt-1 w-full' . ($errors->has('concept') ? ' is-invalid' : ''))
            ->placeholder('Concepto') !!}
    </div>
    
    <div class="mt-4">
        {!! html()->label('Clasificación', 'item_id') !!}
        {!! html()->select('item_id', $classifications->pluck('name', 'id'), isset($project) ? $project->item_id : null)
            ->class('block mt-1 w-full' . ($errors->has('item_id') ? ' is-invalid' : ''))
            ->placeholder('Seleccione una clasificación') !!}
    </div>
    
    <div class="mt-4">
        {!! html()->label('Espacio', 'space_id') !!}
        {!! html()->select('space_id', $spaces->pluck('name', 'id'), isset($project) ? $project->space_id : null)
            ->class('block mt-1 w-full' . ($errors->has('space_id') ? ' is-invalid' : ''))
            ->placeholder('Seleccione un espacio') !!}
    </div>    
</div>