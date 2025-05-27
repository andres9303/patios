<?php

namespace App\Livewire\Table\Report;

use App\Models\Doc;
use App\Models\Event\TimeTable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Components\SetUp\Exportable;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;

final class TimetableEventTable extends PowerGridComponent
{
    public string $tableName = 'timetable-event-table';

    public function setUp(): array
    {
        $this->showCheckBox();

        return [
            PowerGrid::exportable('Programacion_Agenda_' . Carbon::now()->format('Y-m-d'))
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
        return TimeTable::query()
            ->join('items as activity', 'time_tables.item_id', 'activity.id')
            ->join('items as category', 'activity.item_id', 'category.id')
            ->join('people', 'time_tables.person_id', 'people.id')
            ->join('users', 'time_tables.user_id', 'users.id')
            ->where('time_tables.company_id', Auth::user()->current_company_id)
            ->select([
                'time_tables.*',
                'category.name as category_name',
                'activity.name as activity_name',
                'people.name as person_name',
                'users.name as user_name',
            ])
            ->orderBy('time_tables.id', 'desc')
            ->orderBy('time_tables.date', 'desc')
            ->limit(400);
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('category_name')
            ->add('activity_name')
            ->add('person_name')
            ->add('user_name')
            ->add('date')
            ->add('percentage')
            ->add('percentage_format', fn($row) => number_format($row->percentage, 2))
            ->add('text');
    }

    public function columns(): array
    {
        return [
            Column::make('Fecha', 'date', 'time_tables.date')
                ->sortable()
                ->searchable(),

            Column::make('Categoría', 'category_name', 'category.name')
                ->sortable()
                ->searchable(),

            Column::make('Actividad', 'activity_name', 'activity.name')
                ->sortable()
                ->searchable(),

            Column::make('Responsable', 'person_name', 'people.name')
                ->sortable()
                ->searchable(),

            Column::make('% Ocupación', 'percentage_format', 'time_tables.percentage')
                ->sortable()
                ->visibleInExport(false),

            Column::make('% Ocupación', 'percentage', 'time_tables.percentage')
                ->visibleInExport(true)
                ->hidden(),

            Column::make('Descripción', 'text', 'time_tables.text')
                ->sortable()
                ->searchable(),

            Column::make('Usuario', 'user_name', 'users.name')
                ->sortable()
                ->searchable(),

            Column::action('')
        ];
    }

    public function filters(): array
    {
        return [
            Filter::inputText('category_name', 'category.name')->operators(['contains']),
            Filter::inputText('activity_name', 'activity.name')->operators(['contains']),
            Filter::inputText('person_name', 'people.name')->operators(['contains']),
            Filter::inputText('user_name', 'users.name')->operators(['contains']),
            Filter::datePicker('date', 'time_tables.date'),
            Filter::inputText('text', 'time_tables.text')->operators(['contains']),
        ];
    }
}
