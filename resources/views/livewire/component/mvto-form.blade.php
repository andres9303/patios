<div>
    <div x-data="{ showModal: false }">
        <div class="min-h-screen bg-gray-100 p-2">
            <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Resumen (siempre visible) -->
                <div class="lg:col-span-1 bg-white rounded-xl shadow-2xl p-6 space-y-6">
                    <h2 class="text-2xl font-bold text-gray-800">Resumen</h2>
                    {!! Html::form('POST', route($route))->class('space-y-4')->open() !!}
                        <div>
                            {!! Html::label('Código Factura', 'code')->class('block text-sm font-medium text-gray-700') !!}
                            {!! Html::text('code', $code ?? '')
                                ->class('mt-1 block w-full bg-white text-gray-900 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500')
                                ->attribute('placeholder', 'Ingrese código factura') !!}
                            @error('code') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            {!! Html::label('Número Factura', 'num')->class('block text-sm font-medium text-gray-700') !!}
                            {!! Html::text('num', $num ?? '')
                                ->class('mt-1 block w-full bg-white text-gray-900 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500')
                                ->attribute('placeholder', 'Ingrese número factura') !!}
                            @error('num') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            {!! Html::label('Proveedor', 'person_id')->class('block text-sm font-medium text-gray-700') !!}
                            {!! Html::select('person_id', $persons->pluck('name', 'id'), isset($person) ? $person->id : null)
                                ->placeholder('Seleccione un proveedor')
                                ->class('mt-1 block w-full bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500') !!}
                            @error('person_id') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            {!! Html::label('Fecha factura', 'date')->class('block text-sm font-medium text-gray-700') !!}
                            {!! Html::date('date', $date ?? now())
                                ->class('mt-1 block w-full bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500') !!}
                            @error('date') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                        </div>
                        <!-- Órdenes Agregadas -->
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800 mt-4">Cantidades</h3>
                            <div class="overflow-x-auto">
                                <table class="min-w-full bg-white divide-y divide-gray-300">
                                    <thead class="bg-indigo-600">
                                        <tr>
                                            <th class="px-4 py-2 text-sm font-medium text-white text-left">Producto</th>
                                            <th class="px-4 py-2 text-sm font-medium text-white text-left">Unidad</th>
                                            <th class="px-4 py-2 text-sm font-medium text-white text-left">Cantidad</th>
                                            <th class="px-4 py-2 text-sm font-medium text-white text-left">Valor</th>
                                            <th class="px-4 py-2 text-sm font-medium text-white text-left">%IVA</th>
                                            @if($isActivities)<th class="px-4 py-2 text-sm font-medium text-white text-left">Actividad</th>@endif
                                            <th class="px-4 py-2 text-sm font-medium text-white text-left">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200">
                                        @foreach($orders as $order)
                                        <tr class="hover:bg-gray-100">
                                            <td class="px-4 py-2 text-sm text-gray-700">{{ $order->product->name }}</td>
                                            <td class="px-4 py-2 text-sm text-gray-700">{{ $order->unit->name }}</td>
                                            <td class="px-4 py-2">
                                                <input wire:change="updateOrder({{ $order->id }}, 'cant', $event.target.value)" value="{{ $order->cant }}" type="number" class="w-16 bg-gray-50 border border-gray-300 rounded-md p-1 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                            </td>
                                            <td class="px-4 py-2">
                                                <input wire:change="updateOrder({{ $order->id }}, 'value', $event.target.value)" value="{{ $order->value }}" type="number" class="w-16 bg-gray-50 border border-gray-300 rounded-md p-1 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                            </td>
                                            <td class="px-4 py-2">
                                                <input wire:change="updateOrder({{ $order->id }}, 'iva', $event.target.value)" value="{{ $order->iva }}" type="number" class="w-16 bg-gray-50 border border-gray-300 rounded-md p-1 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                            </td>
                                            @if($isActivities)<td class="px-4 py-2 text-sm text-gray-700">
                                                <!--select de activities-->
                                                <select wire:change="updateOrder({{ $order->id }}, 'activity_id', $event.target.value)" class="w-full bg-white border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                                    <option value="">Seleccione una actividad</option>
                                                    @foreach($activities as $activity)
                                                    <option value="{{ $activity->id }}" @if($order->activity_id == $activity->id) selected @endif>{{ $activity->code }} - {{ $activity->name }}</option>
                                                    @endforeach
                                                </select>
                                            </td>@endif
                                            <td class="px-4 py-2">
                                                <button type="button" wire:click="removeOrder({{ $order->id }})" class="text-red-500 hover:text-red-600">Eliminar</button>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <!-- Botón para agregar productos en pantallas pequeñas -->
                            <div class="block lg:hidden">
                                <button type="button" @click="showModal = true" class="w-full bg-green-600 hover:bg-green-700 text-white py-2 rounded-md shadow transition duration-150 mt-4">
                                    Agregar Productos
                                </button>
                            </div>
                        </div>
                        <div class="mt-4 space-y-1">
                            <p class="text-sm text-gray-700">Subtotal: {{ number_format($subtotal, 2) }}</p>
                            <p class="text-sm text-gray-700">IVA: {{ number_format($iva, 2) }}</p>
                            <p class="text-lg font-bold text-gray-800">Total: {{ number_format($total, 2) }}</p>
                        </div>
                        <div>
                            <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white py-2 rounded-md shadow-lg transition duration-150">Guardar</button>
                        </div>
                    {!! Html::form()->close() !!}
                </div>
            
                <!-- Productos y Órdenes en pantallas grandes -->
                <div class="lg:col-span-2 hidden lg:block bg-white rounded-xl shadow-2xl p-6 space-y-6">
                    <!-- Filtro de Categorías -->
                    <div class="bg-gray-100 p-4 rounded-md shadow">
                        <h3 class="text-xl font-semibold text-gray-800 mb-2">Filtrar Productos</h3>
                        {!! Html::select('selectedCategory', $categories->pluck('name', 'id')->prepend('Todos', 'all'), 'all')
                            ->attribute('wire:model', 'selectedCategory')
                            ->attribute('wire:change', 'loadProducts')
                            ->class('w-full p-2 bg-white border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500') !!}
                    </div>
            
                    <!-- Lista de Productos -->
                    <div>
                        <h3 class="text-xl font-semibold text-gray-800 mb-4">Productos Disponibles</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($products as $product)
                            <div class="bg-gray-50 p-4 rounded-md shadow hover:shadow-lg transition-shadow duration-150">
                                <h4 class="font-semibold text-gray-800">{{ $product->name }}</h4>
                                <h5 class="font-semibold text-xs text-gray-800">{{ $product->item->name }}</h6>
                                <h6 class="text-xs text-gray-800">{{ $product->unit->name }}</h6>
                                <button wire:click="addProduct({{ $product->id }})" class="mt-3 w-full bg-indigo-600 hover:bg-indigo-700 text-white py-2 rounded-md transition duration-150">Agregar</button>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    
        <!-- Modal para agregar productos en pantallas pequeñas -->
        <div x-show="showModal" class="fixed inset-0 flex items-center justify-center z-50 px-6" style="background: rgba(0, 0, 0, 0.5)">
            <div x-data="{ showConfirmation: false, confirmationText: '' }"
                 x-on:product-added.window="showConfirmation = true; confirmationText = $event.detail.message; setTimeout(() => showConfirmation = false, 2000)"
                 class="bg-white rounded-xl shadow-2xl p-6 max-w-xl w-full" @click.away="showModal = false">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold text-gray-800">Productos Disponibles</h3>
                    <button @click="showModal = false" class="text-gray-600 hover:text-gray-800 text-2xl font-bold">&times;</button>
                </div>
                <!-- Mensaje de confirmación -->
                <div x-show="showConfirmation" x-transition class="mb-4 p-2 bg-green-100 text-green-700 rounded">
                    <span x-text="confirmationText"></span>
                </div>
                <!-- Filtro de Categorías -->
                <div class="bg-gray-100 p-4 rounded-md shadow mb-4">
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Filtrar Productos</h3>
                    {!! Html::select('selectedCategory', $categories->pluck('name', 'id')->prepend('Todos', 'all'), 'all')
                        ->attribute('wire:model', 'selectedCategory')
                        ->attribute('wire:change', 'loadProducts')
                        ->class('w-full p-2 bg-white border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500') !!}
                </div>
                <!-- Lista de Productos -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Productos</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($products as $product)
                        <div class="bg-gray-50 p-4 rounded-md shadow hover:shadow-lg transition-shadow duration-150">
                            <h4 class="font-semibold text-gray-800">{{ $product->name }}</h4>
                            <button wire:click="addProduct({{ $product->id }})" class="mt-3 w-full bg-indigo-600 hover:bg-indigo-700 text-white py-2 rounded-md transition duration-150">Agregar</button>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>