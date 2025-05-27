<?php

namespace App\Livewire\Table\Project;

use App\Models\Config\Variable;
use App\Models\Master\Space;
use App\Models\Project\Project;
use App\Models\Project\Schedule;
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

final class ScheduleTable extends PowerGridComponent
{
    use WithExport;

    public string $tableName = 'lpg-schedule-table';

    public function setUp(): array
    {
        return [
            PowerGrid::exportable('Schedules_' . Carbon::now()->format('Y-m-d'))
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
        return Schedule::query()
            ->where('schedules.company_id', Auth::user()->current_company_id) // Filtro por company_id del usuario
            ->join('projects', 'projects.id', '=', 'schedules.project_id')
            ->join('spaces', 'spaces.id', '=', 'schedules.space_id')
            ->select([
                'schedules.*',
                'projects.name as project_name',
                'spaces.name as space_name',
            ])
            ->orderBy('schedules.date')
            ->orderBy('schedules.id');
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        $var = Variable::where('cod', 'PrgDP')->first();
        $days = $var->concept ?? 25;

        return PowerGrid::fields()
        ->add('date', fn($row) => $this->getStyledValue($row, $row->date, $days))
        ->add('date2', fn($row) => $this->getStyledValue($row, Carbon::parse($row->date)->addDays($row->days)->format('Y-m-d'), $days))
        ->add('days', fn($row) => $this->getStyledValue($row, $row->days, $days))
        ->add('cant', fn($row) => $this->getStyledValue($row, $row->cant, $days))
        ->add('saldo', fn($row) => $this->getStyledValue($row, $row->saldo, $days))
        ->add('text', fn($row) => $this->getStyledValue($row, $row->text, $days))
        ->add('state')
        ->add('project_name', fn($row) => $this->getStyledValue($row, $row->project_name, $days))
        ->add('space_name', fn($row) => $this->getStyledValue($row, $row->space_name, $days));
    }

    private function getStyledValue($row, $value, $days): string
    {
        $dateWithDays = Carbon::parse($row->date)->addDays($row->days);
        $now = Carbon::now();

        if ($dateWithDays <= $now) {
            $class = 'bg-red-600 text-white w-full h-full text-center';
        } elseif ($dateWithDays <= $now->addDays($days)) {
            $class = 'bg-yellow-600 text-white w-full h-full text-center';
        } else {
            $class = 'w-full h-full text-center';
        }

        return '<div class="' . $class . '">' . $value . '</div>';
    }

    public function columns(): array
    {
        return [
            Column::make('Espacio', 'space_name', 'spaces.name')
                ->sortable()
                ->searchable(),

            Column::make('Proyecto', 'project_name', 'projects.name')
                ->sortable()
                ->searchable(),

            Column::make('Fecha', 'date', 'schedules.date')
                ->sortable()
                ->searchable(),

            Column::make('Siguiente', 'date2', 'schedules.date2')
                ->sortable()
                ->searchable(),

            Column::make('Días', 'days', 'schedules.days')
                ->sortable()
                ->searchable(),

            Column::make('Cantidad', 'cant', 'schedules.cant')
                ->sortable()
                ->searchable(),

            Column::make('Saldo', 'saldo', 'schedules.saldo')
                ->sortable()
                ->searchable(),

            Column::make('Descripción', 'text', 'schedules.text')
                ->sortable()
                ->searchable(),

            Column::make('Estado', 'state', 'schedules.state')
                ->toggleable(false, 'Activo', 'Inactivo')
                ->sortable()
                ->searchable(),

            Column::action('')
        ];
    }

    public function filters(): array
    {
        return [
            Filter::select('space_name', 'spaces.id')->dataSource(Space::where('state', 1)->where('company_id', Auth::user()->current_company_id)->orderBy('name')->get())->optionLabel('name')->optionValue('id'),
            Filter::datetimepicker('date', 'schedules.date'),
            Filter::inputText('text', 'schedules.text')->operators(['contains']),
            Filter::inputText('state', 'schedules.state')->operators(['contains']),
            Filter::inputText('project_name', 'projects.name')->operators(['contains']),
        ];
    }

    public function actionsFromView($row): View
    {
        $buttons = [
            [
                'name' => 'Editar',
                'route' => 'schedule.edit',
                'params' => ['schedule' => $row->id],
                'color' => 'blue',
                'icon' => 'fa fa-edit',
                'type' => 'button',
                'active' => true
            ],
            [
                'name' => 'Programar',
                'route' => 'schedule.schedule',
                'params' => ['schedule' => $row->id],
                'color' => 'green',
                'icon' => 'fa fa-calendar',
                'type' => 'button',
                'active' => $row->saldo >= 0 && !Project::where('state', 1)->where('company_id', Auth::user()->current_company_id)->where('schedule_id', $row->id)->where('type', 1)->exists()
            ],
            [
                'name' => 'Eliminar',
                'route' => 'schedule.destroy',
                'params' => ['schedule' => $row->id],
                'color' => 'red',
                'icon' => 'fa fa-trash-alt',
                'type' => 'delete',
                'active' => true
            ],
        ];

        return view('components.row-buttons', compact('buttons'));
    }
}
