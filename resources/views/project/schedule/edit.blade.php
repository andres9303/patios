<x-app-layout>
    <x-slot name="header">
        Proyectos - Programaciones - Editar Programación
    </x-slot>

<div class="flex justify-center mt-8">
    <div class="w-full md:w-1/2">
        <div class="mb-4">
            {!! html()->form('PUT', route('schedule.update', ['schedule' => $schedule]))->open() !!}
                @include('project.schedule.components.form-elements')

                @if ($errors->any())
                    <div class="flex items-center bg-red-100 text-red-500 text-sm font-bold px-4 py-3" role="alert">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>
                                    <i class="fas fa-exclamation-circle mr-2"></i>
                                    <span class="align-middle">{{ $error }}</span>
                                </li>
                            @endforeach
                        </ul>                                
                    </div>
                @endif

                <div class="mt-6">
                    {!! html()->button('<i class="fa fa-save"></i> Actualizar', 'submit')
                        ->class('bg-blue-500 block mt-1 w-full hover:bg-blue-600 text-white py-2 px-4')
                    !!}
                </div>
            {!! html()->form()->close() !!}
        </div>
    </div>
</div>
</x-app-layout>
