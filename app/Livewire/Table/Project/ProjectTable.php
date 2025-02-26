<?php

namespace App\Livewire\Table\Project;

use App\Models\Project\Project;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\View;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Components\SetUp\Exportable;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\Traits\WithExport;

final class ProjectTable extends PowerGridComponent
{
    use WithExport;

    public string $tableName = 'lpg-project-table';

    public function setUp(): array
    {
        $this->showCheckBox();

        return [
            PowerGrid::exportable('Proyectos_' . Carbon::now()->format('Y-m-d'))
                ->striped()
                ->type(Exportable::TYPE_XLS, Exportable::TYPE_CSV),
            PowerGrid::header()
                ->showSearchInput(),
            PowerGrid::footer()
                ->showPerPage()
                ->showRecordCount(),
        ];
    }

    public function datasource(): Builder
    {
        return Project::query()
            ->where('company_id', auth()->user()->current_company_id) // Filtro por company_id del usuario
            ->leftJoin('items', 'items.id', '=', 'projects.item_id')
            ->leftJoin('spaces', 'spaces.id', '=', 'projects.space_id')
            ->select([
                'projects.*',
                'items.name as item_name',
                'spaces.name as space_name',
            ])
            ->orderBy('projects.type')
            ->orderBy('projects.name');
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('name')
            ->add('text')
            ->add('state')
            ->add('concept')
            ->add('type')
            ->add('item_name')
            ->add('space_name');
    }

    public function columns(): array
    {
        return [
            Column::make('Tipo', 'type', 'projects.type')
                ->toggleable(false, 'Proyecto', 'Presupuesto')
                ->sortable()
                ->searchable(),

            Column::make('Nombre', 'name', 'projects.name')
                ->sortable()
                ->searchable(),

            Column::make('Descripción', 'text', 'projects.text')
                ->sortable()
                ->searchable(),

            Column::make('Estado', 'state', 'projects.state')
                ->toggleable(false, 'Activo', 'Inactivo')
                ->sortable()
                ->searchable(),

            Column::make('Clasificación', 'item_name', 'items.name')
                ->sortable()
                ->searchable(),

            Column::make('Espacio', 'space_name', 'spaces.name')
                ->sortable()
                ->searchable(),

            Column::action('')
        ];
    }

    public function filters(): array
    {
        return [
            Filter::inputText('name', 'projects.name')->operators(['contains']),
            Filter::inputText('text', 'projects.text')->operators(['contains']),
            Filter::inputText('state', 'projects.state')->operators(['contains']),
            Filter::inputText('concept', 'projects.concept')->operators(['contains']),
            Filter::inputText('type', 'projects.type')->operators(['contains']),
            Filter::inputText('item_name', 'items.name')->operators(['contains']),
            Filter::inputText('space_name', 'spaces.name')->operators(['contains']),
        ];
    }

    public function actionsFromView($row): View
    {
        $buttons = [
            [
                'name' => 'Editar',
                'route' => 'project.edit',
                'params' => ['project' => $row->id],
                'color' => 'blue',
                'icon' => 'fa fa-edit',
                'type' => 'button',
                'active' => true
            ],
            [
                'name' => 'Actividades',
                'route' => 'activity.index',
                'params' => ['project' => $row->id],
                'color' => 'yellow',
                'icon' => 'fas fa-tasks',
                'type' => 'button',
                'active' => true
            ],
            [
                'name' => 'Eliminar',
                'route' => 'project.destroy',
                'params' => ['project' => $row->id],
                'color' => 'red',
                'icon' => 'fa fa-trash-alt',
                'type' => 'delete',
                'active' => true
            ],
        ];

        return view('components.row-buttons', compact('buttons'));   
    }
}
