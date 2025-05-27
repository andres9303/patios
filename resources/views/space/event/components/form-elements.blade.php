<div class="text-gray-500">   

    <div class="mt-4">
        {!! html()->label('Tipo de Evento', 'item_id') !!}
        {!! html()->select('item_id', $eventTypes->pluck('name', 'id'), isset($event) ? $event->item_id : null)
            ->class('block mt-1 w-full' . ($errors->has('item_id') ? ' is-invalid' : ''))
            ->placeholder('Tipo de Evento') !!}
    </div>

    <div class="mt-4">
        {!! html()->label('Espacio', 'space_id') !!}
        {!! html()->select('space_id', $spaces->pluck('name', 'id'), isset($event) ? $event->space_id : null)
            ->class('block mt-1 w-full' . ($errors->has('space_id') ? ' is-invalid' : ''))
            ->placeholder('Espacio') !!}
    </div>
    
    <div class="mt-4">
        {!! html()->label('Fecha', 'date') !!}
        {!! html()->date('date', isset($event) ? $event->date : null)
            ->class('block mt-1 w-full' . ($errors->has('date') ? ' is-invalid' : ''))
            ->placeholder('Fecha del Evento') !!}
    </div>
    
    <div class="mt-4">
        {!! html()->label('Hora', 'time') !!}
        {!! html()->time('time', isset($event) ? $event->time : null)
            ->class('block mt-1 w-full' . ($errors->has('time') ? ' is-invalid' : ''))
            ->placeholder('Hora del Evento') !!}
    </div>
    
    <div class="mt-4">
        {!! html()->label('Nombre del Evento', 'title') !!}
        {!! html()->text('title', isset($event) ? $event->title : null)
            ->class('block mt-1 w-full' . ($errors->has('title') ? ' is-invalid' : ''))
            ->placeholder('Nombre del Evento') !!}
    </div>
    
    <div class="mt-4">
        {!! html()->label('Descripción', 'text') !!}
        {!! html()->textarea('text', isset($event) ? $event->text : null)
            ->class('block mt-1 w-full' . ($errors->has('text') ? ' is-invalid' : ''))
            ->placeholder('Descripción del Evento') !!}
    </div>
    
    <div class="mt-4">
        {!! html()->label('Ubicación', 'location') !!}
        {!! html()->text('location', isset($event) ? $event->location : null)
            ->class('block mt-1 w-full' . ($errors->has('location') ? ' is-invalid' : ''))
            ->placeholder('Ubicación del Evento') !!}
    </div>
</div>