<div class="text-gray-500">    
    <div class="mt-4">
        {!! html()->label('Nombre de la categoría', 'name') !!}
        {!! html()->text('name', isset($category_activity) ? $category_activity->name : null)
            ->class('block mt-1 w-full' . ($errors->has('name') ? ' is-invalid' : ''))
            ->placeholder('Nombre de la categoría') !!}
    </div>
    
    <div class="mt-4">
        {!! html()->label('Descripción', 'text') !!}
        {!! html()->textarea('text', isset($category_activity) ? $category_activity->text : null)
            ->class('block mt-1 w-full' . ($errors->has('text') ? ' is-invalid' : ''))
            ->placeholder('Descripción de la categoría') !!}
    </div>
    
    <div class="mt-4">
        {!! html()->label('Orden', 'order') !!}
        {!! html()->number('order', isset($category_activity) ? $category_activity->order : 0)
            ->class('block mt-1 w-full' . ($errors->has('order') ? ' is-invalid' : ''))
            ->placeholder('Orden') !!}
    </div>
</div>