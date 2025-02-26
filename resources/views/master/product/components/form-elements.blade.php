<div class="text-gray-500">    
    <div class="mt-4">
        {!! html()->label('Código', 'code') !!}
        {!! html()->text('code', isset($product) ? $product->code : null)
            ->class('block mt-1 w-full' . ($errors->has('code') ? ' is-invalid' : ''))
            ->placeholder('Código del producto') !!}
    </div>
    
    <div class="mt-4">
        {!! html()->label('Nombre', 'name') !!}
        {!! html()->text('name', isset($product) ? $product->name : null)
            ->class('block mt-1 w-full' . ($errors->has('name') ? ' is-invalid' : ''))
            ->placeholder('Nombre del producto') !!}
    </div>
    
    <div class="mt-4">
        {!! html()->label('Unidad', 'unit_id') !!}
        {!! html()->select('unit_id', $units->pluck('name', 'id'), isset($product) ? $product->unit_id : null)
            ->class('block mt-1 w-full' . ($errors->has('unit_id') ? ' is-invalid' : ''))
            ->placeholder('Seleccione una unidad') !!}
    </div>
    
    <div class="mt-4">
        {!! html()->label('Estado', 'state') !!}
        {!! html()->checkbox('state', isset($product) ? $product->state : false)
            ->class('block mt-1 w-full' . ($errors->has('state') ? ' is-invalid' : '')) !!}
    </div>
    
    <div class="mt-4">
        {!! html()->label('¿Es inventariable?', 'isinventory') !!}
        {!! html()->checkbox('isinventory', isset($product) ? $product->isinventory : false)
            ->class('block mt-1 w-full' . ($errors->has('isinventory') ? ' is-invalid' : '')) !!}
    </div>
    
    <div class="mt-4">
        {!! html()->label('Categoría', 'item_id') !!}
        {!! html()->select('item_id', $items->pluck('name', 'id'), isset($product) ? $product->item_id : null)
            ->class('block mt-1 w-full' . ($errors->has('item_id') ? ' is-invalid' : ''))
            ->placeholder('Seleccione una categoría') !!}
    </div>
</div>