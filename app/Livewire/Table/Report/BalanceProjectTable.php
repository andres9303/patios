<?php

namespace App\Livewire\Table\Report;

use App\Models\Project\Activity;
use App\Models\Project\Project;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Components\SetUp\Exportable;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\Rule;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\Traits\WithExport;

final class BalanceProjectTable extends PowerGridComponent
{
    use WithExport;

    public string $tableName = 'lpg-balance-project-table';
    public string $primaryKey = 'activities.id';
    public int $menuIdAdvance = 402;

    public function setUp(): array
    {
        $this->showCheckBox();

        return [
            PowerGrid::exportable('Balance_Proyectos_' . Carbon::now()->format('Y-m-d'))
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
            ->join('projects', 'projects.id', '=', 'activities.project_id')
            ->join('units', 'units.id', '=', 'activities.unit_id')
            ->leftJoin('spaces', 'spaces.id', '=', 'projects.space_id')
            ->where('projects.type', 1)
            ->where('projects.company_id', Auth::user()->current_company_id)
            ->select([
                'activities.id',
                'projects.name as project_name',
                'activities.code',
                'activities.name as activity_name',
                'units.name as unit_name',
                'spaces.name as space_name',
                'projects.state',
                'activities.cant as budget_quantity',
                DB::raw('(SELECT SUM(mvtos.cant) FROM docs as d 
                    INNER JOIN mvtos ON mvtos.doc_id = d.id 
                    WHERE d.menu_id = ' . $this->menuIdAdvance . ' 
                    and d.company_id = ' . Auth::user()->current_company_id . ' 
                    and d.state = 1 
                    and mvtos.activity_id = activities.id 
                    and mvtos.state = 1 
                    and mvtos.cant > 0) as advanced_quantity'),
                'activities.cost',
                DB::raw('(SELECT SUM(mvtos.cant * mvtos.costu) FROM mvtos 
                         INNER JOIN docs ON docs.id = mvtos.doc_id 
                         WHERE mvtos.activity_id = activities.id 
                         AND docs.company_id = ' . Auth::user()->current_company_id . ' 
                         AND mvtos.cant > 0
                         AND mvtos.costu > 0
                         AND docs.state = 1 
                         AND mvtos.state = 1) as real_cost'),
                DB::raw('(activities.cant * activities.cost) as total_cost')
            ])
            ->groupBy('activities.id', 'projects.name', 'activities.code', 'activities.name', 'activities.cant', 'units.name', 'activities.cost', 'spaces.name')
            ->orderBy('projects.name')
            ->orderBy('activities.code');
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
            ->add('space_name')
            ->add('state', fn($row) => $this->getStyledValue($row->state))
            ->add('budget_quantity')
            ->add('budget_quantity_format', fn ($row) => number_format($row->budget_quantity, 2))
            ->add('advanced_quantity')
            ->add('advanced_quantity_format', fn ($row) => number_format($row->advanced_quantity, 2))
            ->add('estimated_cost', fn ($row) => $row->cost * $row->advanced_quantity)
            ->add('estimated_cost_format', fn ($row) => number_format($row->cost * $row->advanced_quantity))
            ->add('real_cost')
            ->add('real_cost_format', fn ($row) => number_format($row->real_cost))
            ->add('cost')
            ->add('cost_format', fn ($row) => number_format($row->cost))
            ->add('actual_cost', fn ($row) => $row->real_cost / ($row->advanced_quantity > 0 ? $row->advanced_quantity : 1))
            ->add('actual_cost_format', fn ($row) => number_format($row->real_cost / ($row->advanced_quantity > 0 ? $row->advanced_quantity : 1)))
            ->add('total_cost')
            ->add('total_cost_format', fn ($row) => number_format($row->total_cost))
            ->add('projected_cost', fn ($row) => $row->budget_quantity * ($row->real_cost / ($row->advanced_quantity > 0 ? $row->advanced_quantity : 1)))
            ->add('projected_cost_format', fn ($row) => number_format($row->budget_quantity * ($row->real_cost / ($row->advanced_quantity > 0 ? $row->advanced_quantity : 1))));
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
            Column::make('Proyecto', 'project_name', 'projects.name')
                ->sortable()
                ->searchable(),

            Column::make('Cód. Actividad', 'code', 'activities.code')
                ->sortable()
                ->searchable(),

            Column::make('Actividad', 'activity_name', 'activities.name')
                ->sortable()
                ->searchable(),

            Column::make('Unidad', 'unit_name', 'units.name')
                ->sortable()
                ->searchable(),

            Column::make('Estado', 'state', 'projects.state')
                ->sortable()
                ->searchable(),

            Column::make('Espacio', 'space_name', 'spaces.name')
                ->sortable()
                ->searchable(),

            Column::make('Cant. Presupuestada', 'budget_quantity_format')
                ->sortable()
                ->searchable()
                ->visibleInExport(false),

            Column::make('Cant. Presupuestada', 'budget_quantity')
                ->visibleInExport(true)
                ->hidden(),

            Column::make('Cant. Avanzada', 'advanced_quantity_format')
                ->sortable()
                ->searchable()
                ->visibleInExport(false),

            Column::make('Cant. Avanzada', 'advanced_quantity')
                ->visibleInExport(true)
                ->hidden(),

            Column::make('Costo Estimado', 'estimated_cost_format')
                ->sortable()
                ->searchable()
                ->visibleInExport(false),

            Column::make('Costo Estimado', 'estimated_cost')
                ->visibleInExport(true)
                ->hidden(),

            Column::make('Costo Real', 'real_cost_format')
                ->sortable()
                ->searchable()
                ->visibleInExport(false),

            Column::make('Costo Real', 'real_cost')
                ->visibleInExport(true)
                ->hidden(),

            Column::make('CostoU Estimado', 'cost_format')
                ->sortable()
                ->searchable()
                ->visibleInExport(false),

            Column::make('CostoU Estimado', 'cost')
                ->visibleInExport(true)
                ->hidden(),

            Column::make('CostoU Actual', 'actual_cost_format')
                ->sortable()
                ->searchable()
                ->visibleInExport(false),

            Column::make('CostoU Actual', 'actual_cost')
                ->visibleInExport(true)
                ->hidden(),

            Column::make('Costo Total', 'total_cost_format')
                ->sortable()
                ->searchable()
                ->visibleInExport(false),

            Column::make('Costo Total', 'total_cost')
                ->visibleInExport(true)
                ->hidden(),

            Column::make('Costo Proyectado', 'projected_cost_format')
                ->sortable()
                ->searchable()
                ->visibleInExport(false),

            Column::make('Costo Proyectado', 'projected_cost')
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
            Filter::inputText('space_name', 'spaces.name')->operators(['contains']),
        ];
    }
}
