<div class="text-gray-500">
    <div class="mt-4">
        {!! html()->label('Fecha')->for('date') !!}
        {!! html()->date('date', $manage_ticket->date ?? null)->class('block mt-1 w-full' . ($errors->has('date') ? ' is-invalid' : '')) !!}
    </div>
    <div class="mt-4">
        {!! html()->label('Huesped/Tercero')->for('person_id') !!} 
        @can('view-menu', 'person')
            <a href="{{ route('person.create') }}" class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-blue-500 border border-transparent rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500" role="button"><i class="fa fa-plus"></i></a>
        @endcan
        {!! html()->select('person_id', $persons->pluck('name', 'id'), $manage_ticket->person_id ?? null)->class('block mt-1 w-full' . ($errors->has('person_id') ? ' is-invalid' : ''))->placeholder('Seleccionar Huesped/Tercero') !!}
    </div>
    <div class="mt-4">
        {!! html()->label('Locación/Habitación')->for('location_id') !!}
        {!! html()->select('location_id', $locations->pluck('name', 'id'), $manage_ticket->location_id ?? null)->class('block mt-1 w-full' . ($errors->has('location_id') ? ' is-invalid' : ''))->placeholder('Seleccionar Locación/Habitación') !!}
    </div>
    <div class="mt-4">
        {!! html()->label('Categoría')->for('category_id') !!}
        {!! html()->select('category_id', $categories->pluck('name', 'id'), $manage_ticket->category_id ?? null)->class('block mt-1 w-full' . ($errors->has('category_id') ? ' is-invalid' : ''))->placeholder('Seleccionar Categoría') !!}
    </div>
    <div class="mt-4">
        {!! html()->label('Sub-Categoría (Opcional)')->for('category2_id') !!}
        {!! html()->select('category2_id', $categories->pluck('name', 'id'), $manage_ticket->category2_id ?? null)->class('block mt-1 w-full' . ($errors->has('category2_id') ? ' is-invalid' : ''))->placeholder('Seleccionar Sub-Categoría') !!}
    </div>
    <div class="mt-4">
        {!! html()->label('Asignar usuario')->for('user2_id') !!}
        {!! html()->select('user2_id', $users->pluck('name', 'id'), $manage_ticket->user2_id ?? null)->class('block mt-1 w-full' . ($errors->has('user_id') ? ' is-invalid' : ''))->placeholder('Seleccionar Usuario') !!}
    </div>
    <div class="mt-4">
        {!! html()->label('Descripción')->for('text') !!}
        {!! html()->textarea('text', $manage_ticket->text ?? null)->class('block mt-1 w-full' . ($errors->has('text') ? ' is-invalid' : ''))->placeholder('Descripción') !!}
    </div>
</div>