<x-app-layout>
    <x-slot name="header">
        Proyectos - Proyecto {{ $project->name }} - Actividades
    </x-slot>

    <x-crud-index>
        <x-slot name="actions">
            <a href="{{ route('project.index') }}" class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-gray-500 border border-transparent rounded-md hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500" role="button">
                <i class="fa fa-arrow-left mr-1"></i> Volver
            </a>
            <a href="{{ route('activity.create', ['project' => $project]) }}" class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-blue-500 border border-transparent rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500" role="button">
                <i class="fa fa-plus mr-1"></i> Nueva Actividad
            </a>
        </x-slot>

        <x-slot name="content">
            <livewire:table.project.activity-table :project="$project"/>
        </x-slot>
    </x-crud-index>
</x-app-layout>