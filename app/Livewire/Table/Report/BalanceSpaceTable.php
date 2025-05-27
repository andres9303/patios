<?php

namespace App\Livewire\Table\Report;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\Traits\WithExport;
use PowerComponents\LivewirePowerGrid\Components\SetUp\Exportable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Master\Space;

final class BalanceSpaceTable extends PowerGridComponent
{
    public string $tableName = 'balance-space-table';
    public string $primaryKey = 'spaces.id';

    public function setUp(): array
    {
        $this->showCheckBox();

        return [
            PowerGrid::exportable('Balance_Espacios_' . Carbon::now()->format('Y-m-d'))
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
        return Space::query()
            ->join('mvtos', 'mvtos.space_id', '=', 'spaces.id')
            ->join('docs', 'docs.id', '=', 'mvtos.doc_id')
            ->where('spaces.state', 1)
            ->where('mvtos.state', 1)
            ->where('docs.state', 1)
            ->where('mvtos.cant', '<>', 0)
            ->where('mvtos.costu', '<>', 0)
            ->where('spaces.company_id', Auth::user()->current_company_id)
            ->where('docs.company_id', Auth::user()->current_company_id)
            ->select([
                'spaces.id',
                'spaces.name as space_name',
                DB::raw('SUM(mvtos.cant * mvtos.costu) as cost_real'),
                DB::raw("SUM(CASE WHEN docs.date >= '" . now()->subMonths(5)->startOfMonth()->format('Y-m-d') . "' 
                            AND docs.date < '" . now()->subMonths(4)->startOfMonth()->format('Y-m-d') . "' 
                            THEN mvtos.cant * mvtos.costu ELSE 0 END) as Month5"),
                DB::raw("SUM(CASE WHEN docs.date >= '" . now()->subMonths(4)->startOfMonth()->format('Y-m-d') . "' 
                            AND docs.date < '" . now()->subMonths(3)->startOfMonth()->format('Y-m-d') . "' 
                            THEN mvtos.cant * mvtos.costu ELSE 0 END) as Month4"),
                DB::raw("SUM(CASE WHEN docs.date >= '" . now()->subMonths(3)->startOfMonth()->format('Y-m-d') . "' 
                            AND docs.date < '" . now()->subMonths(2)->startOfMonth()->format('Y-m-d') . "' 
                            THEN mvtos.cant * mvtos.costu ELSE 0 END) as Month3"),
                DB::raw("SUM(CASE WHEN docs.date >= '" . now()->subMonths(2)->startOfMonth()->format('Y-m-d') . "' 
                            AND docs.date < '" . now()->subMonths(1)->startOfMonth()->format('Y-m-d') . "' 
                            THEN mvtos.cant * mvtos.costu ELSE 0 END) as Month2"),
                DB::raw("SUM(CASE WHEN docs.date >= '" . now()->subMonths(1)->startOfMonth()->format('Y-m-d') . "' 
                            AND docs.date < '" . now()->startOfMonth()->format('Y-m-d') . "' 
                            THEN mvtos.cant * mvtos.costu ELSE 0 END) as Month1"),
                DB::raw("SUM(CASE WHEN docs.date >= '" . now()->startOfMonth()->format('Y-m-d') . "' 
                            AND docs.date < '" . now()->addMonth(1)->startOfMonth()->format('Y-m-d') . "' 
                            THEN mvtos.cant * mvtos.costu ELSE 0 END) as MonthNow"),
            ])
            ->groupBy('spaces.id', 'spaces.name')
            ->orderBy('spaces.name');
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('space_name')
            ->add('cost_real')
            ->add('cost_real_format', fn ($row) => number_format($row->cost_real))
            ->add('MonthNow')
            ->add('MonthNow_format', fn ($row) => number_format($row->MonthNow))
            ->add('Month1')
            ->add('Month1_format', fn ($row) => number_format($row->Month1))
            ->add('Month2')
            ->add('Month2_format', fn ($row) => number_format($row->Month2))
            ->add('Month3')
            ->add('Month3_format', fn ($row) => number_format($row->Month3))
            ->add('Month4')
            ->add('Month4_format', fn ($row) => number_format($row->Month4))
            ->add('Month5')
            ->add('Month5_format', fn ($row) => number_format($row->Month5));
    }

    private function getMonthTitles()
    {
        $months = [];
        $now = now();
        
        // Mes actual
        $months['MonthNow'] = $now->format('m-Y');
        
        // Meses anteriores
        for ($i = 1; $i <= 5; $i++) {
            $month = $now->copy()->subMonths($i);
            $months['Month' . $i] = $month->format('m-Y');
        }
        
        return $months;
    }

    public function columns(): array
    {
        $monthTitles = $this->getMonthTitles();
        
        return [
            Column::make('Espacio', 'space_name', 'spaces.name')
                ->sortable()
                ->searchable(),
                
            Column::make('Costo Total', 'cost_real_format')
                ->sortable()
                ->searchable()
                ->visibleInExport(false),

            Column::make('Costo Total', 'cost_real')
                ->visibleInExport(true)
                ->hidden(),
                
            Column::make($monthTitles['MonthNow'], 'MonthNow_format')
                ->sortable()
                ->searchable()
                ->visibleInExport(false),

            Column::make($monthTitles['MonthNow'], 'MonthNow')
                ->visibleInExport(true)
                ->hidden(),
                
            Column::make($monthTitles['Month1'], 'Month1_format')
                ->sortable()
                ->searchable()
                ->visibleInExport(false),

            Column::make($monthTitles['Month1'], 'Month1')
                ->visibleInExport(true)
                ->hidden(),
                
            Column::make($monthTitles['Month2'], 'Month2_format')
                ->sortable()
                ->searchable()
                ->visibleInExport(false),

            Column::make($monthTitles['Month2'], 'Month2')
                ->visibleInExport(true)
                ->hidden(),
                
            Column::make($monthTitles['Month3'], 'Month3_format')
                ->sortable()
                ->searchable()
                ->visibleInExport(false),

            Column::make($monthTitles['Month3'], 'Month3')
                ->visibleInExport(true)
                ->hidden(),
                
            Column::make($monthTitles['Month4'], 'Month4_format')
                ->sortable()
                ->searchable()
                ->visibleInExport(false),

            Column::make($monthTitles['Month4'], 'Month4')
                ->visibleInExport(true)
                ->hidden(),
                
            Column::make($monthTitles['Month5'], 'Month5_format')
                ->sortable()
                ->searchable()
                ->visibleInExport(false),

            Column::make($monthTitles['Month5'], 'Month5')
                ->visibleInExport(true)
                ->hidden(),
        ];
    }
}
