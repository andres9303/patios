<div class="bg-white lg:px-8 lg:pt-6 lg:pb-8 lg:mb-4">
    {{-- Información de la Plantilla Mejorada --}}
    <div class="bg-gray-50 p-6 rounded-lg shadow-sm mb-8 border border-gray-200">
        <h4 class="text-md font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-200">Detalles de la Plantilla</h4>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-x-6 gap-y-4">
            <div>
                <strong class="block text-sm font-medium text-gray-900">Plantilla:</strong>
                <p class="mt-1 text-sm text-gray-900">{{ $template->name }}</p>
                <input type="hidden" name="template_id" value="{{ $template->id }}">
            </div>
            <div>
                <strong class="block text-sm font-medium text-gray-900">Espacio:</strong>
                <p class="mt-1 text-sm text-gray-900">{{ $template->space ? $template->space->name : 'N/A' }}</p>
                <input type="hidden" name="space_id" value="{{ $template->space_id }}">
            </div>
            <div>
                <strong class="block text-sm font-medium text-gray-900">Fecha:</strong>
                {!! html()->date('date')
                    ->id('date')
                    ->class('mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500')
                    ->required()
                    ->value(now()->format('Y-m-d')) !!}
            </div>
            <div class="lg:col-span-2">
                <strong class="block text-sm font-medium text-gray-900">Descripción:</strong>
                <p class="mt-1 text-sm text-gray-900 whitespace-pre-line">{{ $template->description ?? 'N/A' }}</p>
            </div>
        </div>
    </div>

    {{-- Campos de la Plantilla --}}
    @if($sortedFields->isNotEmpty())
        <div class="md:grid md:grid-cols-2 md:gap-6">
            @foreach($sortedFields as $field)
                <div class="p-4 border border-gray-200 rounded-md">
                    {{-- Usamos html()->label para consistencia, aunque aquí es solo visual --}}
                    {!! html()->label($field->name . ($field->is_required ? '<span class="text-red-500">*</span>' : ' (opcional)'), "field_preview_{$field->id}")
                        ->class('block text-sm font-medium text-gray-700') !!}

                    @php
                        $fieldTypeName = $field->typeField ? $field->typeField->name : 'Desconocido';
                        $inputName = "answers[{$field->id}]";
                        $descriptionInputName = "descriptions[{$field->id}]";
                    @endphp

                    <div class="mt-2">
                        @switch($fieldTypeName)
                            @case('Texto')
                                {!! html()->text($inputName, isset($answers) ? optional($answers->where('field_id', $field->id)->first())->value_text : null)
                                    ->id("field_preview_{$field->id}")
                                    ->class('mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500')
                                    ->required($field->is_required) !!}
                                @break
                            @case('Entero')
                                {!! html()->input('number', $inputName, isset($answers) ? optional($answers->where('field_id', $field->id)->first())->value_number : null)
                                    ->id("field_preview_{$field->id}")
                                    ->class('mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500')
                                    ->required($field->is_required) !!}
                                @break
                            @case('Decimal')
                                {!! html()->input('number', $inputName, isset($answers) ? optional($answers->where('field_id', $field->id)->first())->value_decimal : null)
                                    ->id("field_preview_{$field->id}")
                                    ->attribute('step', 'any')
                                    ->class('mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500')
                                    ->required($field->is_required) !!}
                                @break
                            @case('Fecha')
                                {!! html()->date($inputName, isset($answers) ? optional($answers->where('field_id', $field->id)->first())->value_date : null)
                                    ->id("field_preview_{$field->id}")
                                    ->class('mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500')
                                    ->required($field->is_required) !!}
                                @break
                            @case('Hora')
                                {!! html()->time($inputName, isset($answers) ? optional($answers->where('field_id', $field->id)->first())->value_time : null)
                                    ->id("field_preview_{$field->id}")
                                    ->class('mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500')
                                    ->required($field->is_required) !!}
                                @break
                            @case('Fecha y Hora')
                                {!! html()->datetimeLocal($inputName, isset($answers) ? optional($answers->where('field_id', $field->id)->first())->value_datetime : null)
                                    ->id("field_preview_{$field->id}")
                                    ->class('mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500')
                                    ->required($field->is_required) !!}
                                @break
                            @case('Booleano')
                                {!! html()->select($inputName, isset($answers) ? optional($answers->where('field_id', $field->id)->first())->value_boolean : ['1' => 'Sí', '0' => 'No'])
                                    ->id("field_preview_{$field->id}")
                                    ->class('mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md')
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
                            {!! html()->textarea($descriptionInputName, isset($answers) ? optional($answers->where('field_id', $field->id)->first())->description : null)
                                ->id("field_desc_preview_{$field->id}")
                                ->rows(2)
                                ->class('mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500') !!}
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    @endif    
</div>