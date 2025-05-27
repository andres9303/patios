<?php

namespace App\Livewire\Table\Project;

use App\Models\Config\Item;
use App\Models\Master\Space;
use App\Models\Project\Project;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
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
            ->where('projects.company_id', Auth::user()->current_company_id) // Filtro por company_id del usuario
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
            ->add('state_det', fn($row) => $this->getStyledValue($row->state))
            ->add('concept')
            ->add('type')
            ->add('item_name')
            ->add('space_name');
    }

    private function getStyledValue($value): string
    {
        $class = match ($value) {
            2 => 'bg-green-600 text-white w-full h-full text-center',
            0 => 'bg-red-600 text-white w-full h-full text-center',
            1 => 'bg-indigo-600 text-white w-full h-full text-center',
        };

        $text = match ($value) {
            2 => 'Finalizado',
            0 => 'Inactivo',
            1 => 'Activo',
        };

        return '<div class="' . $class . '">' . $text . '</div>';
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

            Column::make('Estado', 'state_det', 'projects.state')
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
            Filter::select('state', 'projects.state')->dataSource([['id' => 1, 'name' => 'Activo'], ['id' => 2, 'name' => 'Finalizado'], ['id' => 0, 'name' => 'Inactivo']])->optionLabel('name')->optionValue('id'),
            Filter::inputText('concept', 'projects.concept')->operators(['contains']),
            Filter::select('type', 'projects.type')->dataSource([['id' => 1, 'name' => 'Proyecto'], ['id' => 0, 'name' => 'Presupuesto']])->optionLabel('name')->optionValue('id'),
            Filter::select('item_name', 'items.id')->dataSource(Item::where('catalog_id', 20801)->orderBy('order')->get())->optionLabel('name')->optionValue('id'),
            Filter::select('space_name', 'spaces.id')->dataSource(Space::where('state', 1)->where('company_id', Auth::user()->current_company_id)->orderBy('name')->get())->optionLabel('name')->optionValue('id'),
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
                'name' => 'Finalizar',
                'route' => 'project.complete',
                'params' => ['project' => $row->id],
                'color' => 'green',
                'icon' => 'fas fa-check',
                'type' => 'button',
                'active' => $row->state == 1
            ],
            [
                'name' => 'Reabrir',
                'route' => 'project.open',
                'params' => ['project' => $row->id],
                'color' => 'blue',
                'icon' => 'fas fa-unlock',
                'type' => 'button',
                'active' => $row->state == 2
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
