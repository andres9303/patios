<div class="text-gray-500">
    <div class="mt-4">
        {!! html()->label('Código Categoría')->for('code') !!}
        {!! html()->text('code', $category->code ?? null)
            ->class('block mt-1 w-full' . ($errors->has('code') ? ' is-invalid' : ''))
            ->placeholder('Código Categoría') !!}
    </div>
    <div class="mt-4">
        {!! html()->label('Nombre Categoría')->for('name') !!}
        {!! html()->text('name', $category->name ?? null)
            ->class('block mt-1 w-full' . ($errors->has('name') ? ' is-invalid' : ''))
            ->placeholder('Nombre Categoría') !!}
    </div>
    <div class="mt-4">
        {!! html()->label('Descripción')->for('text') !!}
        {!! html()->textarea('text', $category->text ?? null)
            ->class('block mt-1 w-full' . ($errors->has('text') ? ' is-invalid' : ''))
            ->placeholder('Descripción') !!}
    </div>
    <div class="mt-4">
        {!! html()->label('Días de vencimiento')->for('days') !!}
        {!! html()->number('days', $category->days ?? null)
            ->class('block mt-1 w-full' . ($errors->has('days') ? ' is-invalid' : ''))
            ->placeholder('Días') !!}
    </div>
    <div class="mt-4">
        {!! html()->label('Categoría Padre')->for('ref_id') !!}
        {!! html()->select('ref_id', $categories->pluck('name', 'id'), $category->ref_id ?? null)
            ->class('block mt-1 w-full' . ($errors->has('ref_id') ? ' is-invalid' : ''))
            ->placeholder('Categoría Padre') !!}
    </div>    
    <div class="mt-4">
        {!! html()->label('Estado')->for('state') !!}
        {!! html()->checkbox('state', $category->state ?? null)
            ->class('block mt-1 w-full' . ($errors->has('state') ? ' is-invalid' : ''))
            ->placeholder('Estado') !!}
    </div>
</div>