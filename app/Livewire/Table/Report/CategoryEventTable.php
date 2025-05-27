<?php

namespace App\Livewire\Table\Report;

use App\Models\Event\TimeTable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Components\SetUp\Exportable;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\Traits\WithExport;

final class CategoryEventTable extends PowerGridComponent
{
    use WithExport;
    public string $tableName = 'category-event-table';
    public string $primaryKey = 'activity.id';

    protected function getMonthLabels(): array
    {
        $meses = [
            1 => 'Ene', 2 => 'Feb', 3 => 'Mar', 4 => 'Abr',
            5 => 'May', 6 => 'Jun', 7 => 'Jul', 8 => 'Ago',
            9 => 'Sep', 10 => 'Oct', 11 => 'Nov', 12 => 'Dic'
        ];
        
        $labels = [];
        for ($i = 0; $i <= 5; $i++) {
            $fecha = now()->subMonths($i);
            $mes = $meses[(int)$fecha->format('n')];
            $labels["Month{$i}"] = $mes . ' ' . $fecha->format('Y');
        }
        return $labels;
    }

    public function setUp(): array
    {
        $this->showCheckBox();

        return [
            PowerGrid::exportable('Categoria_Evento_' . Carbon::now()->format('Y-m-d'))
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
        $query = TimeTable::query()
            ->join('items as activity', 'time_tables.item_id', 'activity.id')
            ->join('items as category', 'activity.item_id', 'category.id')
            ->where('time_tables.company_id', Auth::user()->current_company_id)
            ->where('time_tables.date', '>=', now()->subMonths(5)->startOfMonth())
            ->select([
                'activity.id',
                'category.name as category_name',
                'activity.name as activity_name',
                DB::raw('COUNT(time_tables.id) as total_events'),
                DB::raw('ROUND(AVG(time_tables.percentage), 2) as total_occupancy'),
            ]);

        for ($i = 0; $i <= 5; $i++) {
            $startDate = now()->subMonths($i)->startOfMonth()->format('Y-m-d');
            $endDate = ($i == 0) 
                ? now()->addMonth()->startOfMonth()->format('Y-m-d')
                : now()->subMonths($i - 1)->startOfMonth()->format('Y-m-d');
            
            $query->selectRaw("SUM(CASE WHEN time_tables.date >= '{$startDate}' 
                                AND time_tables.date < '{$endDate}' 
                                THEN 1 ELSE 0 END) as event_count_{$i}");
            
            $query->selectRaw("ROUND(AVG(CASE WHEN time_tables.date >= '{$startDate}' 
                                AND time_tables.date < '{$endDate}' 
                                THEN time_tables.percentage ELSE NULL END), 2) as occupancy_{$i}");
        }

        return $query
            ->groupBy('activity.id', 'category.name', 'activity.name')
            ->orderBy('category.name')
            ->orderBy('activity.name');
    }

    public function fields(): PowerGridFields
    {
        $fields = PowerGrid::fields()
            ->add('category_name')
            ->add('activity_name')
            ->add('total_events')
            ->add('total_occupancy', fn($row) => $row->total_occupancy ? $row->total_occupancy . '%' : '0%');

        for ($i = 0; $i <= 5; $i++) {
            $fields->add("event_count_{$i}")
                   ->add("occupancy_{$i}", fn($row) => $row->{"occupancy_{$i}"} ? $row->{"occupancy_{$i}"} . '%' : '0%');
        }

        return $fields;
    }

    public function columns(): array
    {
        $columns = [
            Column::make('Categoría', 'category_name', 'category.name')
                ->sortable()
                ->searchable(),

            Column::make('Actividad', 'activity_name', 'activity.name')
                ->sortable()
                ->searchable(),

            Column::make('Total Eventos', 'total_events')
                ->sortable(),
                
            Column::make('Ocupación Total', 'total_occupancy')
                ->sortable(),
        ];

        foreach ($this->getMonthLabels() as $index => $label) {
            $i = str_replace('Month', '', $index);
            
            $columns[] = Column::make("Eventos {$label}", "event_count_{$i}")
                ->sortable();
                
            $columns[] = Column::make("Ocupación {$label}", "occupancy_{$i}")
                ->sortable();
        }

        return $columns;
    }

    public function filters(): array
    {
        return [
            Filter::inputText('category_name', 'category.name')->operators(['contains']),
            Filter::inputText('activity_name', 'activity.name')->operators(['contains']),
            Filter::datePicker('date_from', 'time_tables.date'),
            Filter::datePicker('date_to', 'time_tables.date'),
        ];
    }
}