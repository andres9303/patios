<?php

namespace App\Livewire\Table\Report;

use App\Models\Project\Activity;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Components\SetUp\Exportable;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;

final class PendingActivityTable extends PowerGridComponent
{
    public string $tableName = 'pending-activity-table';
    public int $menuId = 402;
    public string $primaryKey = 'activities.id';

    public function setUp(): array
    {
        $this->showCheckBox();

        return [
            PowerGrid::exportable('Avances_Pendientes_Proyecto_' . Carbon::now()->format('Y-m-d'))
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
        // Subconsulta para calcular la cantidad avanzada de cada actividad
        $advancedQuantities = DB::table('mvtos')
            ->select(
                'mvtos.activity_id',
                DB::raw('COALESCE(SUM(mvtos.cant), 0) as advanced_quantity')
            )
            ->join('docs', 'docs.id', '=', 'mvtos.doc_id')
            ->where('docs.menu_id', $this->menuId)
            ->where('mvtos.state', 1)
            ->where('docs.state', 1)
            ->groupBy('mvtos.activity_id');

        return Activity::query()
            ->select([
                'activities.*',
                'projects.name as project_name',
                'units.name as unit_name',
                'users.name as user_name',
                DB::raw('COALESCE(aq.advanced_quantity, 0) as advanced_quantity'),
                DB::raw('activities.cant - COALESCE(aq.advanced_quantity, 0) as pending_quantity')
            ])
            ->join('projects', 'projects.id', '=', 'activities.project_id')
            ->join('users', 'users.id', '=', 'activities.user_id')
            ->leftJoin('units', 'units.id', '=', 'activities.unit_id')
            ->leftJoinSub($advancedQuantities, 'aq', function($join) {
                $join->on('activities.id', '=', 'aq.activity_id');
            })
            ->where('activities.state', 1)
            ->where('projects.state', 1)
            ->where('projects.company_id', Auth::user()->current_company_id)
            ->having('pending_quantity', '>', 0) // Solo actividades con cantidad pendiente
            ->orderBy('activities.end_date', 'asc') // Ordenar por fecha de vencimiento
            ->orderBy('projects.name')
            ->orderBy('activities.code');
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('project_name', fn($row) => $this->getStyledValue($row, $row->project_name))
            ->add('code', fn($row) => $this->getStyledValue($row, $row->code))
            ->add('name', fn($row) => $this->getStyledValue($row, $row->name))
            ->add('cant')
            ->add('cant_format', fn($row) => $this->getStyledValue($row, number_format($row->cant)))
            ->add('advanced_quantity')
            ->add('advanced_quantity_format', fn($row) => $this->getStyledValue($row, number_format($row->advanced_quantity)))
            ->add('pending_quantity')
            ->add('pending_quantity_format', fn($row) => $this->getStyledValue($row, number_format($row->pending_quantity)))
            ->add('unit_name', fn($row) => $this->getStyledValue($row, $row->unit_name))
            ->add('user_name', fn($row) => $this->getStyledValue($row, $row->user_name))
            ->add('start_date')
            ->add('start_date_format', fn($row) => $this->getStyledValue(
                $row, 
                $row->start_date ? Carbon::parse($row->start_date)->format('d/m/Y') : ''
            ))
            ->add('end_date')
            ->add('end_date_format', fn($row) => $this->getStyledValue(
                $row, 
                $row->end_date ? Carbon::parse($row->end_date)->format('d/m/Y') : ''
            ));
    }
    
    private function getStyledValue($row, $value): string
    {
        $endDate = $row->end_date ? Carbon::parse($row->end_date) : null;
        $startDate = $row->start_date ? Carbon::parse($row->start_date) : null;
        $now = Carbon::now();
        
        $class = '';
        
        // Si la fecha de fin está dentro de 3 días o ya pasó
        if ($endDate && $endDate->diffInDays($now, false) >= 0) {
            $class = 'bg-red-600 text-white';
        } 
        // Si la fecha de inicio ya pasó
        elseif ($startDate && $startDate->lt($now)) {
            $class = 'bg-yellow-600 text-white';
        }
        
        return $class ? '<span class="' . $class . '">' . $value . '</span>' : $value;
    }

    public function columns(): array
    {
        return [
            Column::make('Proyecto', 'project_name')
                ->sortable()
                ->searchable(),

            Column::make('Código', 'code')
                ->sortable()
                ->searchable(),

            Column::make('Actividad', 'name')
                ->sortable()
                ->searchable(),

            Column::make('Cant. Pendiente', 'pending_quantity_format')
                ->sortable()
                ->visibleInExport(false),

            Column::make('Cant. Pendiente', 'pending_quantity')
                ->visibleInExport(true)
                ->hidden(),

            Column::make('Cant. Avanzada', 'advanced_quantity_format')
                ->sortable()
                ->visibleInExport(false),

            Column::make('Cant. Avanzada', 'advanced_quantity')
                ->visibleInExport(true)
                ->hidden(),

            Column::make('Cant. Total', 'cant_format')
                ->sortable()
                ->visibleInExport(false),

            Column::make('Cant. Total', 'cant')
                ->visibleInExport(true)
                ->hidden(),

            Column::make('Unidad', 'unit_name')
                ->sortable()
                ->searchable(),

            Column::make('Fecha Inicio', 'start_date_format', 'start_date')
                ->sortable()
                ->searchable(),

            Column::make('Fecha Límite', 'end_date_format', 'end_date')
                ->sortable()
                ->searchable(),

            Column::make('Responsable', 'user_name')
                ->sortable()
                ->searchable(),
                
            Column::action('')
        ];
    }

    public function filters(): array
    {
        return [
            Filter::inputText('project_name', 'projects.name')->operators(['contains']),
            Filter::inputText('code', 'activities.code')->operators(['contains']),
            Filter::inputText('name', 'activities.name')->operators(['contains']),
        ];
    }
}
