<div class="text-gray-500">    
    <div class="mt-4">
        {!! html()->label('Nombre', 'name') !!}
        {!! html()->text('name', isset($space) ? $space->name : null)
            ->class('block mt-1 w-full' . ($errors->has('name') ? ' is-invalid' : ''))
            ->placeholder('Nombre del espacio') !!}
    </div>
    
    <div class="mt-4">
        {!! html()->label('Descripción', 'text') !!}
        {!! html()->textarea('text', isset($space) ? $space->text : null)
            ->class('block mt-1 w-full' . ($errors->has('text') ? ' is-invalid' : ''))
            ->placeholder('Descripción del espacio') !!}
    </div>
    
    <div class="mt-4">
        {!! html()->label('Orden', 'order') !!}
        {!! html()->number('order', isset($space) ? $space->order : null)
            ->class('block mt-1 w-full' . ($errors->has('order') ? ' is-invalid' : ''))
            ->placeholder('Orden') !!}
    </div>
    
    <div class="mt-4">
        {!! html()->label('Estado', 'state') !!}
        {!! html()->checkbox('state', isset($product) ? $product->state : false)
            ->class('block mt-1 w-full' . ($errors->has('state') ? ' is-invalid' : '')) !!}
    </div>
    
    <div class="mt-4">
        {!! html()->label('Categoría', 'item_id') !!}
        {!! html()->select('item_id', $categories->pluck('name', 'id'), isset($space) ? $space->item_id : null)
            ->class('block mt-1 w-full' . ($errors->has('item_id') ? ' is-invalid' : ''))
            ->placeholder('Seleccione una categoría') !!}
    </div>
    
    <div class="mt-4">
        {!! html()->label('Clase', 'item2_id') !!}
        {!! html()->select('item2_id', $classes->pluck('name', 'id'), isset($space) ? $space->item2_id : null)
            ->class('block mt-1 w-full' . ($errors->has('item2_id') ? ' is-invalid' : ''))
            ->placeholder('Seleccione una clase') !!}
    </div>
    
    <div class="mt-4">
        {!! html()->label('Capacidad Instalada', 'cant') !!}
        {!! html()->number('cant', isset($space) ? $space->cant : null)
            ->class('block mt-1 w-full' . ($errors->has('cant') ? ' is-invalid' : ''))
            ->placeholder('Capacidad instalada')
            ->attribute('step', '0.0001') !!}
    </div>
    
    <div class="mt-4">
        {!! html()->label('Espacio Padre', 'space_id') !!}
        {!! html()->select('space_id', $spaces->pluck('name', 'id'), isset($space) ? $space->space_id : null)
            ->class('block mt-1 w-full' . ($errors->has('space_id') ? ' is-invalid' : ''))
            ->placeholder('Seleccione un espacio padre') !!}
    </div>
</div>