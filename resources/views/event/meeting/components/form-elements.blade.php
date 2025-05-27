<div class="text-gray-500">  
    <div class="mt-4">
        {!! html()->label('Categoría', 'item_id') !!}
        {!! html()->select('item_id', $categories->pluck('name', 'id'), isset($meeting) ? $meeting->item_id : null)
            ->class('block mt-1 w-full' . ($errors->has('item_id') ? ' is-invalid' : ''))
            ->placeholder('Categoría') !!}
    </div>

    <div class="mt-4">
        {!! html()->label('Nombre de la actividad', 'name') !!}
        {!! html()->text('name', isset($meeting) ? $meeting->name : null)
            ->class('block mt-1 w-full' . ($errors->has('name') ? ' is-invalid' : ''))
            ->placeholder('Nombre de la actividad') !!}
    </div>
    
    <div class="mt-4">
        {!! html()->label('Descripción', 'text') !!}
        {!! html()->textarea('text', isset($meeting) ? $meeting->text : null)
            ->class('block mt-1 w-full' . ($errors->has('text') ? ' is-invalid' : ''))
            ->placeholder('Descripción de la actividad') !!}
    </div>
    
    <div class="mt-4">
        {!! html()->label('Orden', 'order') !!}
        {!! html()->number('order', isset($meeting) ? $meeting->order : 0)
            ->class('block mt-1 w-full' . ($errors->has('order') ? ' is-invalid' : ''))
            ->placeholder('Orden') !!}
    </div>
</div>