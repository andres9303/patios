<div class="text-gray-500">    
    <div class="mt-4">
        {!! html()->label('Nombre del Tipo de Evento', 'name') !!}
        {!! html()->text('name', isset($event_type) ? $event_type->name : null)
            ->class('block mt-1 w-full' . ($errors->has('name') ? ' is-invalid' : ''))
            ->placeholder('Nombre del Tipo de Evento') !!}
    </div>
    
    <div class="mt-4">
        {!! html()->label('Descripción', 'text') !!}
        {!! html()->textarea('text', isset($event_type) ? $event_type->text : null)
            ->class('block mt-1 w-full' . ($errors->has('text') ? ' is-invalid' : ''))
            ->placeholder('Descripción del Tipo de Evento') !!}
    </div>
    
    <div class="mt-4">
        {!! html()->label('Orden', 'order') !!}
        {!! html()->number('order', isset($event_type) ? $event_type->order : 0)
            ->class('block mt-1 w-full' . ($errors->has('order') ? ' is-invalid' : ''))
            ->placeholder('Orden') !!}
    </div>
</div>