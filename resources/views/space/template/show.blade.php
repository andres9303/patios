<x-app-layout>
    <x-slot name="header">
        Espacios - Plantillas - Vista Previa: {{ $template->name  }}
    </x-slot>

    <x-crud-index>
        <x-slot name="actions">
            <a href="{{ route('template.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-gray-700 hover:text-gray-500 focus:outline-none focus:border-blue-300 active:bg-gray-100 active:text-gray-800 transition duration-150 ease-in-out">
                <i class="fas fa-arrow-left mr-2"></i> Volver
            </a>
            <a href="{{ route('field.index', ['template' => $template->id]) }}" class="inline-flex items-center px-4 py-2 bg-yellow-500 border border-transparent rounded-md hover:bg-yellow-600 font-semibold text-white focus:outline-none focus:border-yellow-300 transition duration-150 ease-in-out">
                <i class="fas fa-list mr-2"></i> Campos
            </a>
        </x-slot>

        <x-slot name="content">
            <div class="bg-white lg:px-8 lg:pt-6 lg:pb-8 lg:mb-4">
                {{-- Información de la Plantilla Mejorada --}}
                <div class="bg-gray-50 p-6 rounded-lg shadow-sm mb-8 border border-gray-200">
                    <h4 class="text-md font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-200">Detalles de la Plantilla</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-x-6 gap-y-4">
                        <div>
                            <strong class="block text-sm font-medium text-gray-900">Plantilla:</strong>
                            <p class="mt-1 text-sm text-gray-900">{{ $template->name }}</p>
                        </div>
                        <div>
                            <strong class="block text-sm font-medium text-gray-900">Espacio:</strong>
                            <p class="mt-1 text-sm text-gray-900">{{ $template->space ? $template->space->name : 'N/A' }}</p>
                        </div>
                        <div class="lg:col-span-2">
                            <strong class="block text-sm font-medium text-gray-900">Descripción:</strong>
                            <p class="mt-1 text-sm text-gray-900 whitespace-pre-line">{{ $template->description ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>

                @if($sortedFields->isNotEmpty())
                    <div class="space-y-6">
                        @foreach($sortedFields as $field)
                            <div class="p-4 border border-gray-200 rounded-md">
                                {{-- Usamos html()->label para consistencia, aunque aquí es solo visual --}}
                                {!! html()->label($field->name . ($field->is_required ? '<span class="text-red-500">*</span>' : ' (opcional)'), "field_preview_{$field->id}")
                                    ->class('block text-sm font-medium text-gray-700') !!}

                                @php
                                    $fieldTypeName = $field->typeField ? $field->typeField->name : 'Desconocido';
                                    $inputName = "preview[{$field->id}][value]";
                                    $descriptionInputName = "preview[{$field->id}][description_text]";
                                @endphp

                                <div class="mt-2">
                                    @switch($fieldTypeName)
                                        @case('Texto')
                                            {!! html()->text($inputName)
                                                ->id("field_preview_{$field->id}")
                                                ->class('mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500')
                                                ->isReadonly(true)
                                                ->required($field->is_required) !!}
                                            @break
                                        @case('Entero')
                                            {!! html()->input('number', $inputName)
                                                ->id("field_preview_{$field->id}")
                                                ->class('mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500')
                                                ->isReadonly(true)
                                                ->required($field->is_required) !!}
                                            @break
                                        @case('Decimal')
                                            {!! html()->input('number', $inputName)
                                                ->id("field_preview_{$field->id}")
                                                ->attribute('step', 'any')
                                                ->class('mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500')
                                                ->isReadonly(true)
                                                ->required($field->is_required) !!}
                                            @break
                                        @case('Fecha')
                                            {!! html()->date($inputName)
                                                ->id("field_preview_{$field->id}")
                                                ->class('mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500')
                                                ->isReadonly(true)
                                                ->required($field->is_required) !!}
                                            @break
                                        @case('Hora')
                                            {!! html()->time($inputName)
                                                ->id("field_preview_{$field->id}")
                                                ->class('mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500')
                                                ->isReadonly(true)
                                                ->required($field->is_required) !!}
                                            @break
                                        @case('Fecha y Hora')
                                            {!! html()->datetimeLocal($inputName)
                                                ->id("field_preview_{$field->id}")
                                                ->class('mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500')
                                                ->isReadonly(true)
                                                ->required($field->is_required) !!}
                                            @break
                                        @case('Booleano')
                                            {!! html()->select($inputName, ['1' => 'Sí', '0' => 'No'])
                                                ->id("field_preview_{$field->id}")
                                                ->class('mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md')
                                                ->disabled(true)
                                                ->placeholder('Seleccionar...')
                                                ->required($field->is_required) !!}
                                            @break
                                        @default
                                            <p class="mt-1 text-sm text-red-600">Tipo de campo no soportado para vista previa: {{ $fieldTypeName }}</p>
                                    @endswitch
                                </div>

                                @if($field->is_description)
                                    <div class="mt-3">
                                        {!! html()->label('Descripción Adicional (opcional):', "field_desc_preview_{$field->id}")
                                            ->class('block text-xs font-medium text-gray-600') !!}
                                        {!! html()->textarea($descriptionInputName)
                                            ->id("field_desc_preview_{$field->id}")
                                            ->rows(2)
                                            ->class('mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500')
                                            ->isReadonly(true) !!}
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="mt-1 text-sm text-gray-700">Esta plantilla aún no tiene campos activos definidos.</p>
                    <p class="mt-2 text-sm">
                        Puedes agregar campos desde la opción
                        <a href="{{ route('field.index', ['template' => $template->id]) }}" class="text-blue-600 hover:underline">Administrar Campos</a>.
                    </p>
                @endif
            </div>
        </x-slot>
    </x-crud-index>
</x-app-layout>