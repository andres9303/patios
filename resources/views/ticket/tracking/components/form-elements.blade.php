<div class="bg-gray-100 p-4 rounded-lg shadow mb-6 flex items-center">
    <div class="w-1/4 text-left text-gray-700">
        <h3 class="font-semibold text-lg">Ticket #{{ $ticket->id }}</h3>
        <p class="text-sm text-gray-500">{{ $ticket->date }}</p>
        <p class="text-sm"><spam class="font-semibold">Huésped:</spam> {{ $ticket->name }}</p>
        <p class="text-sm"><spam class="font-semibold">Categoría:</spam> {{ $ticket->category2->text }}</p>
        @if ($ticket->updated_at > $ticket->created_at)
        <p class="text-xs"><spam class="font-semibold italic text-indigo-800">[EDITADO]</spam></p>  
        @endif
    </div>
    <div class="w-3/4 text-gray-700 pl-6">        
        <p class="text-lg">{{ $ticket->text }}</p>
        <div class="mt-8">
            <livewire:component.attachment-view
                :attachmentableType="'App\\Models\\Ticket\\Ticket'"
                :attachmentableId="$ticket->id"
            />
        </div>
    </div>
</div>

<div class="mb-8">
    <h3 class="font-semibold text-lg mb-4">Historial</h3>
    @foreach($ticket->trackings as $tracking)
        <div class="flex items-center mb-4 p-4 rounded-lg shadow
            @switch($tracking->type)
                @case(0) bg-gray-100 @break
                @case(1) bg-green-100 @break
                @case(2) bg-yellow-100 @break
                @case(3) bg-red-100 @break
            @endswitch">
            <div class="w-1/4 text-gray-700 flex items-center">
                <p class="text-xs text-gray-500">{{ $tracking->date }}</p>
                <p><i class="fas fa-user-circle text-gray-500 ml-1 mr-1"></i>
                <span class="text-xs font-semibold">{{ $tracking->user->name }}</span></p>
            </div>
            <div class="w-2/4 text-gray-700">
                @if ($tracking->updated_at > $tracking->created_at)
                <p class="text-xs"><spam class="font-semibold italic text-indigo-800">[EDITADO]</spam></p>  
                @endif
                {{ $tracking->text }}
                <div class="mt-8">
                    <livewire:component.attachment-view
                        :attachmentableType="'App\\Models\\Ticket\\Tracking'"
                        :attachmentableId="$tracking->id"
                    />
                </div>
            </div>
            <div class="w-1/4 text-gray-700 text-right">
                @if (auth()->id() === $tracking->user_id)
                <a href="{{ route('resolve-ticket.attachment.index', ['ticket' => $ticket->id, 'resolve_ticket' => $tracking->id]) }}" 
                    class="bottom-2 text-xl right-2 text-yellow-500 hover:text-yellow-700">
                    <i class="fas fa-paperclip"></i>
                </a><br>
                <a href="{{ route('resolve-ticket.edit', ['ticket' => $ticket->id, 'resolve_ticket' => $tracking->id]) }}" 
                    class="bottom-2 text-xl right-2 text-blue-500 hover:text-blue-700">
                    <i class="fas fa-edit"></i>
                </a>
                @endif
            </div>
        </div>
    @endforeach
</div>

<div class="text-gray-500">
    <h3 class="font-semibold text-lg mb-4">Seguimiento</h3>
    <div class="mt-4">
        {!! html()->label('Fecha')->for('date') !!}
        {!! html()->date('date', $resolve_ticket->date ?? now()->format('Y-m-d'))->class('block mt-1 w-full' . ($errors->has('date') ? ' is-invalid' : '')) !!}
    </div>
    <div class="mt-4">
        {!! html()->label('Detalle')->for('text') !!}
        {!! html()->textarea('text', $resolve_ticket->text ?? null)->class('block mt-1 w-full' . ($errors->has('text') ? ' is-invalid' : ''))->placeholder('Detalle del seguimiento') !!}
    </div>
    <div class="mt-4">
        {!! html()->label('Tipo')->for('type') !!}
        {!! html()->select('type', ['0' => 'Seguimiento', '1' => 'Solución', '2' => 'Feedback', '3' => 'Cancelar Ticket'], $resolve_ticket->type ?? 0)->class('block mt-1 w-full' . ($errors->has('type') ? ' is-invalid' : '')) !!}
    </div>
</div>