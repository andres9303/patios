<x-app-layout>
    <x-slot name="header">
        Tickets - Detalle del Ticket - {{ $ticket->id }} - {{ $ticket->date }}
    </x-slot>

    <x-crud-index>
        <x-slot name="actions">
            <a href="{{ url()->previous() }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-gray-700 hover:text-gray-500 focus:outline-none focus:border-blue-300 active:bg-gray-100 active:text-gray-800 transition duration-150 ease-in-out">
                <i class="fas fa-arrow-left mr-2"></i> Volver
            </a>
        </x-slot>

        <x-slot name="content">
            <div class="bg-gray-100 p-4 rounded-lg shadow mb-6 flex items-center">
                <div class="w-1/4 text-left text-gray-700">
                    <h3 class="font-semibold text-lg">Ticket #{{ $ticket->id }}</h3>
                    <p class="text-sm text-gray-500">{{ $ticket->date }}</p>
                    <p class="text-sm"><spam class="font-semibold">Huésped:</spam> {{ $ticket->name }}</p>
                    <p class="text-sm"><spam class="font-semibold">Categoría:</spam> {{ $ticket->category2 ? $ticket->category2->text : '-' }}</p>
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
        </x-slot>
    </x-crud-index>
</x-app-layout>