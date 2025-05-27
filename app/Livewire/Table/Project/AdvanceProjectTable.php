<?php

namespace App\Livewire\Table\Project;

use App\Models\Doc;
use App\Models\Master\Space;
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

final class AdvanceProjectTable extends PowerGridComponent
{
    use WithExport;

    private $menuId = 402;
    public string $tableName = 'lpg-advance-project-table';

    public function setUp(): array
    {
        $this->showCheckBox();

        return [
            PowerGrid::exportable('Avances_Proyecto_' . Carbon::now()->format('Y-m-d'))
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
        return Doc::query()
            ->where('docs.menu_id', $this->menuId)
            ->where('docs.company_id', Auth::user()->current_company_id)
            ->join('people', 'people.id', '=', 'docs.person_id')
            ->join('projects', 'projects.id', '=', 'docs.ref')
            ->leftJoin('spaces', 'spaces.id', '=', 'docs.space_id')
            ->select([
                'docs.*',
                'people.name as person_name',
                'projects.name as project_name',
                'spaces.name as space_name',
            ])
            ->limit(500)
            ->orderBy('docs.id', 'desc')
            ->orderBy('docs.date', 'desc');
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('num')
            ->add('date')
            ->add('person_name')
            ->add('project_name')
            ->add('space_name')
            ->add('state');
    }

    public function columns(): array
    {
        return [
            Column::make('Número avance', 'num', 'docs.num')
                ->sortable()
                ->searchable(),
            Column::make('Fecha avance', 'date', 'docs.date')
                ->sortable()
                ->searchable(),
            Column::make('Responsable', 'person_name', 'people.name')
                ->sortable()
                ->searchable(),
            Column::make('Proyecto', 'project_name', 'projects.name')
                ->sortable()
                ->searchable(),
            Column::make('Espacio', 'space_name', 'spaces.name')
                ->sortable()
                ->searchable(),
            Column::make('Estado', 'state', 'docs.state')
                ->toggleable(false, 'Activo', 'Inactivo')
                ->sortable()
                ->searchable(),

            Column::action('')
        ];
    }

    public function filters(): array
    {
        return [
            Filter::inputText('num', 'docs.num')->operators(['contains']),
            Filter::datetimepicker('date', 'docs.date'),
            Filter::inputText('person_name', 'people.name')->operators(['contains']),
            Filter::inputText('project_name', 'projects.name')->operators(['contains']),
            Filter::select('space_name', 'spaces.id')->dataSource(Space::where('state', 1)->where('company_id', Auth::user()->current_company_id)->orderBy('name')->get())->optionLabel('name')->optionValue('id'),
            Filter::inputText('state', 'docs.state')->operators(['contains']),
        ];
    }

    public function actionsFromView($row): View
    {
        $buttons = [
            [
                'name' => 'Editar',
                'route' => 'advance-project.edit',
                'params' => ['advance_project' => $row->id],
                'color' => 'blue',
                'icon' => 'fa fa-edit',
                'type' => 'button',
                'active' => true
            ],
            [
                'name' => 'Eliminar',
                'route' => 'advance-project.destroy',
                'params' => ['advance_project' => $row->id],
                'color' => 'red',
                'icon' => 'fa fa-trash-alt',
                'type' => 'delete',
                'active' => true
            ],
            [
                'name' => 'Adjuntos',
                'route' => 'advance-project.attachment.index',
                'params' => ['advance_project' => $row->id],
                'color' => 'yellow',
                'icon' => 'fa fa-paperclip',
                'type' => 'button',
                'active' => true
            ],
        ];

        return view('components.row-buttons', compact('buttons'));
    }
}
