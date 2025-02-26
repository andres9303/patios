<div class="text-gray-500">    
    <div class="mt-4">
        {!! html()->label('Nombre de la categoría', 'name') !!}
        {!! html()->text('name', isset($categories_product) ? $categories_product->name : null)
            ->class('block mt-1 w-full' . ($errors->has('name') ? ' is-invalid' : ''))
            ->placeholder('Nombre de la categoría') !!}
    </div>
    
    <div class="mt-4">
        {!! html()->label('Descripción', 'text') !!}
        {!! html()->textarea('text', isset($categories_product) ? $categories_product->text : null)
            ->class('block mt-1 w-full' . ($errors->has('text') ? ' is-invalid' : ''))
            ->placeholder('Descripción de la categoría') !!}
    </div>
    
    <div class="mt-4">
        {!! html()->label('Orden', 'order') !!}
        {!! html()->number('order', isset($categories_product) ? $categories_product->order : 0)
            ->class('block mt-1 w-full' . ($errors->has('order') ? ' is-invalid' : ''))
            ->placeholder('Orden') !!}
    </div>
</div>