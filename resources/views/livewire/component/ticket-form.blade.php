<div>
    <div class="text-gray-500">
        <div class="mt-4">
            {!! html()->label('Fecha')->for('date') !!}
            {!! html()->date('date', $ticket->date ?? null)->class('block mt-1 w-full' . ($errors->has('date') ? ' is-invalid' : '')) !!}
        </div>
        <div class="mt-4">
            {!! html()->label('Huesped/Tercero')->for('name') !!}
            {!! html()->text('name', $ticket->name ?? '')->class('block mt-1 w-full'.($errors->has('name') ? ' is-invalid' : ''))->placeholder('Huesped y/o tercero') !!}
        </div>
        <div class="mt-4">
            {!! html()->label('Locación/Habitación')->for('location_id') !!}
            {!! html()->select('location_id', $locations->pluck('text', 'id'), $ticket->location_id ?? null)->class('block mt-1 w-full' . ($errors->has('location_id') ? ' is-invalid' : ''))->placeholder('Seleccionar Locación/Habitación') !!}
        </div>
        <div class="mt-4">
            {!! html()->label('Categoría')->for('category_id') !!}
            {!! html()->select('category_id', $categories->pluck('text', 'id'), $ticket->category_id ?? null)->attribute('wire:change', 'handleCategoryChange($event.target.value)')->class('block mt-1 w-full' . ($errors->has('category_id') ? ' is-invalid' : ''))->placeholder('Seleccionar Categoría') !!}
        </div>
        <div class="mt-4">
            {!! html()->label('SubCategoría')->for('category2_id') !!}
            {!! html()->select('category2_id', $subcategories->pluck('text', 'id'), $ticket->category2_id ?? null)->class('block mt-1 w-full' . ($errors->has('category2_id') ? ' is-invalid' : ''))->placeholder('Seleccionar SubCategoría') !!}
        </div>
        @if ($isManage)
        <div class="mt-4">
            {!! html()->label('Asignar usuario')->for('user2_id') !!}
            {!! html()->select('user2_id', $users->pluck('name', 'id'), $ticket->user2_id ?? null)->class('block mt-1 w-full' . ($errors->has('user_id') ? ' is-invalid' : ''))->placeholder('Seleccionar Usuario') !!}
        </div>
        @endif        
        <div class="mt-4">
            {!! html()->label('Descripción')->for('text') !!}
            {!! html()->textarea('text', $ticket->text ?? null)->class('block mt-1 w-full' . ($errors->has('text') ? ' is-invalid' : ''))->placeholder('Descripción') !!}
        </div>
        <div class="mt-4">
            {!! html()->label('Prioridad')->for('item_id') !!}
            {!! html()->select('item_id', $priorities->pluck('name', 'id'), $ticket->item_id ?? null)->class('block mt-1 w-full' . ($errors->has('item_id') ? ' is-invalid' : ''))->placeholder('Seleccionar Prioridad') !!}
        </div>
    </div>
</div>
