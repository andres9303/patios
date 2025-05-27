<?php

namespace App\Livewire\Table\Report;

use App\Models\Doc;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Components\SetUp\Exportable;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\Traits\WithExport;

final class ResponsibleActivityTable extends PowerGridComponent
{
    use WithExport;

    private $menuId = 402;
    public string $tableName = 'lpg-responsible-activity-table';
    public string $primaryKey = 'mvtos.id';

    public function setUp(): array
    {
        $this->showCheckBox();

        return [
            PowerGrid::exportable('Avances_Responsable_Proyecto_' . Carbon::now()->format('Y-m-d'))
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
            ->join('mvtos', 'mvtos.doc_id', '=', 'docs.id')
            ->join('activities', 'activities.id', '=', 'mvtos.activity_id')
            ->join('users', 'users.id', '=', 'activities.user_id')
            ->join('projects', 'projects.id', '=', 'activities.project_id')
            ->join('units', 'units.id', '=', 'activities.unit_id')
            ->leftJoin('spaces', 'spaces.id', '=', 'docs.space_id')
            ->where('docs.menu_id', $this->menuId)
            ->where('docs.company_id', Auth::user()->current_company_id)            
            ->select([
                'mvtos.id',
                'mvtos.cant',
                'activities.code as code_activity',
                'activities.name as name_activity',
                'users.name as user_name',
                'activities.cant as cant_activity',
                'units.name as unit_name',
                'docs.date',
                'activities.end_date',
                'projects.name as project_name',
                'spaces.name as space_name',
            ])
            ->limit(500)
            ->orderBy('docs.id', 'desc')
            ->orderBy('docs.date', 'desc');
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('project_name')
            ->add('space_name')
            ->add('code_activity')
            ->add('name_activity')
            ->add('unit_name')
            ->add('date')
            ->add('date_format', fn($row) => Carbon::parse($row->date)->format('Y-m-d'))
            ->add('end_date')
            ->add('end_date_format', fn($row) => Carbon::parse($row->end_date)->format('Y-m-d'))
            ->add('cant_activity')
            ->add('cant_activity_format', fn($row) => number_format($row->cant_activity, 2))
            ->add('cant')
            ->add('cant_format', fn($row) => number_format($row->cant, 2))
            ->add('user_name');
    }

    public function columns(): array
    {
        return [
            Column::make('Proyecto', 'project_name', 'projects.name')
                ->sortable()
                ->searchable(),

            Column::make('Espacio', 'space_name', 'spaces.name')
                ->sortable()
                ->searchable(),

            Column::make('Código actividad', 'code_activity', 'activities.code')
                ->sortable()
                ->searchable(),

            Column::make('Nombre actividad', 'name_activity', 'activities.name')
                ->sortable()
                ->searchable(),

            Column::make('Unidad', 'unit_name', 'units.name')
                ->sortable()
                ->searchable(),

            Column::make('Cantidad actividad', 'cant_activity_format', 'activities.cant')
                ->sortable()
                ->searchable()
                ->visibleInExport(false),

            Column::make('Cantidad actividad', 'cant_activity', 'activities.cant')
                ->visibleInExport(true)
                ->hidden(),

            Column::make('Cantidad', 'cant_format', 'mvtos.cant')
                ->sortable()
                ->searchable()
                ->visibleInExport(false),

            Column::make('Cantidad', 'cant', 'mvtos.cant')
                ->visibleInExport(true)
                ->hidden(),

            Column::make('Fecha Avance', 'date_format', 'docs.date')
                ->sortable()
                ->searchable()
                ->visibleInExport(false),

            Column::make('Fecha Avance', 'date', 'docs.date')
                ->visibleInExport(true)
                ->hidden(),

            Column::make('Fecha fin', 'end_date_format', 'activities.end_date')
                ->sortable()
                ->searchable()
                ->visibleInExport(false),

            Column::make('Fecha fin', 'end_date', 'activities.end_date')
                ->visibleInExport(true)
                ->hidden(),
            
            Column::make('Responsable', 'user_name', 'users.name')
                ->sortable()
                ->searchable(),
        ];
    }

    public function filters(): array
    {
        return [
            Filter::inputText('project_name', 'projects.name')->operators(['contains']),
            Filter::inputText('space_name', 'spaces.name')->operators(['contains']),
            Filter::inputText('code_activity', 'activities.code')->operators(['contains']),
            Filter::inputText('name_activity', 'activities.name')->operators(['contains']),
            Filter::inputText('unit_name', 'units.name')->operators(['contains']),
            Filter::datetimepicker('date', 'docs.date'),
            Filter::datetimepicker('end_date', 'activities.end_date'),
            Filter::inputText('user_name', 'users.name')->operators(['contains']),
        ];
    }
}
