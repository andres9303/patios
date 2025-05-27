<?php

namespace App\Livewire\Table\Report;

use App\Models\Doc;
use App\Models\Mvto;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Components\SetUp\Exportable;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\Traits\WithExport;

final class CostActivityTable extends PowerGridComponent
{
    use WithExport;

    public string $tableName = 'lpg-cost-activity-table';
    public string $primaryKey = 'activities.id';

    public function setUp(): array
    {
        $this->showCheckBox();

        return [
            PowerGrid::exportable('Costos_Actividades_' . Carbon::now()->format('Y-m-d'))
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
        return Mvto::query()
            ->join('docs', 'docs.id', '=', 'mvtos.doc_id')
            ->join('activities', 'activities.id', '=', 'mvtos.activity_id')
            ->join('projects', 'projects.id', '=', 'activities.project_id')
            ->join('units', 'units.id', '=', 'activities.unit_id')
            ->where('mvtos.cant', '<>', 0) 
            ->where('docs.state', 1) 
            ->where('mvtos.state', 1) 
            ->where('docs.company_id', Auth::user()->current_company_id)
            ->where('projects.company_id', Auth::user()->current_company_id)
            ->select([
                'activities.id',
                'projects.name as project_name',
                'activities.code',
                'activities.name as activity_name',
                DB::raw('MAX(activities.cant) as cant'),
                DB::raw('MAX(activities.cost) as cost'),
                'units.name as unit_name',
                DB::raw('SUM(mvtos.cant * mvtos.costu) as cost_real'),
            ])
            ->groupBy('activities.id', 'projects.name', 'activities.code', 'activities.name', 'units.name')
            ->orderBy('projects.name');
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('project_name')
            ->add('code')
            ->add('activity_name')
            ->add('unit_name')
            ->add('cost', fn ($row) => $row->cost * $row->cant)
            ->add('cost_format', fn ($row) => number_format($row->cost * $row->cant))
            ->add('cost_real')
            ->add('cost_real_format', fn ($row) => number_format($row->cost_real));
    }

    public function columns(): array
    {
        return [
            Column::make('Proyecto', 'project_name', 'projects.name')
                ->sortable()
                ->searchable(),

            Column::make('Código Actividad', 'code', 'activities.code')
                ->sortable()
                ->searchable(),

            Column::make('Actividad', 'activity_name', 'activities.name')
                ->sortable()
                ->searchable(),

            Column::make('Unidad', 'unit_name', 'units.name')
                ->sortable()
                ->searchable(),
                
            Column::make('Costo Estimado', 'cost_format')
                ->sortable()
                ->searchable()
                ->visibleInExport(false),

            Column::make('Costo Estimado', 'cost')
                ->visibleInExport(true)
                ->hidden(),

            Column::make('Costo Generado', 'cost_real_format')
                ->sortable()
                ->searchable()
                ->visibleInExport(false),

            Column::make('Costo Generado', 'cost_real')
                ->visibleInExport(true)
                ->hidden(),
        ];
    }

    public function filters(): array
    {
        return [
            Filter::inputText('project_name', 'projects.name')->operators(['contains']),
            Filter::inputText('code', 'activities.code')->operators(['contains']),
            Filter::inputText('activity_name', 'activities.name')->operators(['contains']),
            Filter::inputText('unit_name', 'units.name')->operators(['contains']),
        ];
    }
}
