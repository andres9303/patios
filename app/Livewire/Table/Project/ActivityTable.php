<?php

namespace App\Livewire\Table\Project;

use App\Models\Master\Unit;
use App\Models\Project\Activity;
use App\Models\User;
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

final class ActivityTable extends PowerGridComponent
{
    use WithExport;

    public string $tableName = 'lpg-activity-table';
    public $project; 

    public function setUp(): array
    {
        $this->showCheckBox();

        return [
            PowerGrid::exportable('Actividades_' . $this->project->id . '_' . $this->project->name . Carbon::now()->format('Y-m-d'))
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
        return Activity::query()
            ->where('project_id', $this->project->id)
            ->leftJoin('units', 'units.id', '=', 'activities.unit_id')
            ->leftJoin('users', 'users.id', '=', 'activities.user_id')
            ->select([
                'activities.*',
                'units.name as unit_name',
                'users.name as user_name',
            ])
            ->orderBy('activities.code');
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('code')
            ->add('name')
            ->add('unit_name')
            ->add('text')
            ->add('state')
            ->add('cant')
            ->add('cant_format', fn ($row) => number_format($row->cant, 4))
            ->add('value')
            ->add('value_format', fn ($row) => number_format($row->value))
            ->add('cost')
            ->add('cost_format', fn ($row) => number_format($row->cost))
            ->add('user_name')
            ->add('start_date')
            ->add('start_date_format', fn ($row) => Carbon::parse($row->start_date)->format('d/m/Y'))
            ->add('end_date')
            ->add('end_date_format', fn ($row) => Carbon::parse($row->end_date)->format('d/m/Y'));
    }

    public function columns(): array
    {
        return [
            Column::make('Código', 'code', 'activities.code')
                ->sortable()
                ->searchable(),

            Column::make('Nombre', 'name', 'activities.name')
                ->sortable()
                ->searchable(),

            Column::make('Unidad', 'unit_name', 'units.name')
                ->sortable()
                ->searchable(),

            Column::make('Descripción', 'text', 'activities.text')
                ->sortable()
                ->searchable(),

            Column::make('Estado', 'state', 'activities.state')
                ->toggleable(false, 'Activo', 'Inactivo')
                ->sortable()
                ->searchable(),

            Column::make('Cantidad', 'cant_format', 'activities.cant')
                ->sortable()
                ->searchable()
                ->visibleInExport(false),

            Column::make('Cantidad', 'cant', 'activities.cant')
                ->visibleInExport(true)
                ->hidden(),

            Column::make('Valor unitario', 'value_format', 'activities.value')
                ->sortable()
                ->searchable()
                ->visibleInExport(false),

            Column::make('Valor unitario', 'value', 'activities.value')
                ->visibleInExport(true)
                ->hidden(),

            Column::make('Costo unitario', 'cost_format', 'activities.cost')
                ->sortable()
                ->searchable()
                ->visibleInExport(false),

            Column::make('Costo unitario', 'cost', 'activities.cost')
                ->visibleInExport(true)
                ->hidden(),

            Column::make('Responsable', 'user_name', 'users.name')
                ->sortable()
                ->searchable(),

            Column::make('Fecha de Inicio', 'start_date_format', 'activities.start_date')
                ->sortable()
                ->searchable()
                ->visibleInExport(false),

            Column::make('Fecha de Inicio', 'start_date', 'activities.start_date')
                ->visibleInExport(true)
                ->hidden(),

            Column::make('Fecha de Fin', 'end_date_format', 'activities.end_date')
                ->sortable()
                ->searchable()
                ->visibleInExport(false),

            Column::make('Fecha de Fin', 'end_date', 'activities.end_date')
                ->visibleInExport(true)
                ->hidden(),

            Column::action('')
        ];
    }

    public function filters(): array
    {
        return [
            Filter::inputText('code', 'activities.code')->operators(['contains']),
            Filter::inputText('name', 'activities.name')->operators(['contains']),
            Filter::select('unit_name', 'units.id')->dataSource(Unit::orderBy('name')->get())->optionLabel('name')->optionValue('id'),
            Filter::inputText('text', 'activities.text')->operators(['contains']),
            Filter::inputText('state', 'activities.state')->operators(['contains']),
            Filter::select('user_name', 'users.id')->dataSource(User::orderBy('name')->get())->optionLabel('name')->optionValue('id'),
            Filter::datetimepicker('start_date', 'activities.start_date'),
            Filter::datetimepicker('end_date', 'activities.end_date'),
        ];
    }

    public function actionsFromView($row): View
    {
        $buttons = [
            [
                'name' => 'Editar',
                'route' => 'activity.edit',
                'params' => ['project' => $this->project->id, 'activity' => $row->id],
                'color' => 'blue',
                'icon' => 'fa fa-edit',
                'type' => 'button',
                'active' => true
            ],
            [
                'name' => 'Eliminar',
                'route' => 'activity.destroy',
                'params' => ['project' => $this->project->id, 'activity' => $row->id],
                'color' => 'red',
                'icon' => 'fa fa-trash-alt',
                'type' => 'delete',
                'active' => true
            ],
        ];

        return view('components.row-buttons', compact('buttons'));   
    }
}
