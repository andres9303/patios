<div class="mt-auto flex flex-col space-y-2 button-group">
    @foreach ($buttons as $button)
        @if ($button['active'])
        @if ($button['type'] == 'delete')
        <form style="display:inline;" action="{{ route($button['route'], $button['params'] ?? []) }}" method="POST">
            @csrf
            @method('delete')
            <button type="submit" class="flex w-32 items-center justify-center px-4 py-2 text-sm font-medium text-white bg-{{ $button['color'] ?? 'red' }}-500 border border-transparent rounded-md hover:bg-{{ $button['color'] ?? 'red' }}-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-{{ $button['color'] ?? 'red' }}-500" onclick="return confirm('Esta seguro que desea eliminar?')">
                @if (isset($button['icon']))
                    <i class="{{ $button['icon'] }} mr-1"></i>
                @endif
                {{ $button['name'] }}
            </button>
        </form>            
        @else
        <a href="{{ route($button['route'], $button['params'] ?? []) }}" class="flex w-32 items-center justify-center px-4 py-2 text-sm font-medium text-white bg-{{ $button['color'] ?? 'blue' }}-500 border border-transparent rounded-md hover:bg-{{ $button['color'] ?? 'blue' }}-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-{{ $button['color'] ?? 'blue' }}-500" title="{{ $button['name'] }}" role="button">
            @if (isset($button['icon']))
                <i class="{{ $button['icon'] }} mr-1"></i>
            @endif
            {{ $button['name'] }}
        </a>
        @endif    
        @endif            
    @endforeach
</div>