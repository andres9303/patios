<div class="text-gray-500">
    <div class="mt-4">
        {!! html()->label('Código Locación')->for('code') !!}
        {!! html()->text('code', $location->code ?? null)
            ->class('block mt-1 w-full' . ($errors->has('code') ? ' is-invalid' : ''))
            ->placeholder('Código Locación') !!}
    </div>
    <div class="mt-4">
        {!! html()->label('Nombre Locación')->for('name') !!}
        {!! html()->text('name', $location->name ?? null)
            ->class('block mt-1 w-full' . ($errors->has('name') ? ' is-invalid' : ''))
            ->placeholder('Nombre Locación') !!}
    </div>
    <div class="mt-4">
        {!! html()->label('Descripción')->for('text') !!}
        {!! html()->textarea('text', $location->text ?? null)
            ->class('block mt-1 w-full' . ($errors->has('text') ? ' is-invalid' : ''))
            ->placeholder('Descripción') !!}
    </div>
    <div class="mt-4">
        {!! html()->label('Locación Padre')->for('ref_id') !!}
        {!! html()->select('ref_id', $locations->pluck('name', 'id'), $location->ref_id ?? null)
            ->class('block mt-1 w-full' . ($errors->has('ref_id') ? ' is-invalid' : ''))
            ->placeholder('Locación Padre') !!}
    </div>    
    <div class="mt-4">
        {!! html()->label('Estado')->for('state') !!}
        {!! html()->checkbox('state', $location->state ?? null)
            ->class('block mt-1 w-full' . ($errors->has('state') ? ' is-invalid' : ''))
            ->placeholder('Estado') !!}
    </div>
</div>
