<div>
    <div class="text-gray-500">  
        {!! Html::hidden('doc_id', $docId) !!}

        <div class="mt-4">
            {!! html()->label($isAdvance ? 'Fecha Avance' : 'Fecha', 'date')->class('block text-sm font-medium text-gray-700') !!}
            {!! html()->date('date', isset($date) ? $date : null)
                ->class('mt-1 block w-full bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500') !!}
            @error('date') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
        </div>
        <div class="mt-4">
            {!! Html::label($labelPerson, 'person_id')->class('block text-sm font-medium text-gray-700') !!}
            {!! Html::select('person_id', $persons->pluck('name', 'id'), isset($person) ? $person->id : null)
                ->placeholder('Seleccione un '.$labelPerson)
                ->attribute('wire:model', 'person_id')
                ->class('mt-1 block w-full bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500') !!}
            @error('person_id') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
        </div>   
        <div class="mt-4">
            {!! Html::label('Proyecto', 'project_id')->class('block text-sm font-medium text-gray-700') !!}
            {!! Html::select('project_id', $projects->pluck('name', 'id'), isset($project) ? $project->id : null)
                ->placeholder('Seleccione un Proyecto')
                ->attribute('wire:model', 'project_id')
                ->attribute('wire:change', 'loadActivities')
                ->class('mt-1 block w-full bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500') !!}
            @error('project_id') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
        </div>
        <!-- Actividades -->
        <div class="mt-4">
            <h3 class="block text-sm font-medium text-gray-700">Avance de actividades</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white divide-y divide-gray-300">
                    <thead class="bg-indigo-600">
                        <tr>
                            <th class="px-4 py-2 text-sm font-medium text-white text-left">Actividad</th>
                            <th class="px-4 py-2 text-sm font-medium text-white text-left">Unidad</th>
                            @if($isAdvance)
                            <th class="px-4 py-2 text-sm font-medium text-white text-left">Ptto.</th>
                            <th class="px-4 py-2 text-sm font-medium text-white text-left">Avanzado</th>
                            @endif                            
                            <th class="px-4 py-2 text-sm font-medium text-white text-left">{{ $isAdvance ? 'Avance' : 'Cantidad' }}</th>
                            <th class="px-4 py-2 text-sm font-medium text-white text-left">ValorU</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($orders as $order)
                        <tr class="hover:bg-gray-100">
                            <td class="px-4 py-2 text-sm text-gray-700">{{ $order->activity->name }}</td>
                            <td class="px-4 py-2 text-sm text-gray-700">{{ $order->unit->name }}</td>
                            @if($isAdvance)
                            <td class="px-4 py-2 text-sm text-gray-700">{{ number_format($order->activity->cant, 2) }}</td>
                            <td class="px-4 py-2 text-sm text-gray-700">{{ number_format($order->cant2, 2) }}</td>  
                            @endif                          
                            <td class="px-4 py-2">
                                <input wire:change="updateOrder({{ $order->id }}, 'cant', $event.target.value)" value="{{ $order->cant }}" type="number" step="0.01" class="w-16 bg-gray-50 border border-gray-300 rounded-md p-1 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            </td>
                            @if($isAdvance)
                            <td class="px-4 py-2 text-sm text-gray-700">{{ number_format($order->value) }}</td>
                            @else
                            <td class="px-4 py-2">
                                <input wire:change="updateOrder({{ $order->id }}, 'value', $event.target.value)" value="{{ $order->value }}" type="number" class="w-16 bg-gray-50 border border-gray-300 rounded-md p-1 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            </td>
                            @endif
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>        
        <div class="mt-4">
            {!! Html::label('Observaciones', 'text')->class('block text-sm font-medium text-gray-700') !!}
            {!! Html::textarea('text', $text ?? '')
                ->class('mt-1 block w-full bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500') !!}
            @error('text') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
        </div>
    </div>
</div>
