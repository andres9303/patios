<x-app-layout>
    <x-slot name="header">
        Costos - Ordenes de compra - Crear Orden de compra
    </x-slot>

    <x-crud-index>
        <x-slot name="actions">
            <a href="{{ route('direct-purchase.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-gray-700 hover:text-gray-500 focus:outline-none focus:border-blue-300 active:bg-gray-100 active:text-gray-800 transition duration-150 ease-in-out">
                <i class="fas fa-arrow-left mr-2"></i> Volver
            </a>
        </x-slot>

        <x-slot name="content">
            <div class="m-0 p-0">
                @livewire('component.mvto-form', ['menuId' => $menuId, 'route' => 'direct-purchase.store', 'products' => $products, 'categories' => $categories, 'persons' => $persons, 'isActivities' => 0])
            </div>
        </x-slot>
    </x-crud-index>
</x-app-layout>