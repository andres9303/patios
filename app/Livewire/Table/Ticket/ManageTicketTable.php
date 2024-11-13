<?php

namespace App\Livewire\Table\Ticket;

use App\Models\Ticket\Ticket;
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

final class ManageTicketTable extends PowerGridComponent
{
    public string $tableName = 'lpg-manage-ticket-table';

    use WithExport;

    public function setUp(): array
    {
        $this->showCheckBox();

        return [
            PowerGrid::exportable('Tickets_'.Carbon::now()->format('Y-m-d'))
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
        return Ticket::query()
            ->leftJoin('people', 'people.id', '=', 'tickets.person_id')
            ->leftJoin('locations', 'locations.id', '=', 'tickets.location_id')
            ->leftJoin('categories as category', 'category.id', '=', 'tickets.category_id')
            ->leftJoin('categories as sub_category', 'sub_category.id', '=', 'tickets.category2_id')
            ->leftJoin('users as reporter', 'reporter.id', '=', 'tickets.user_id')
            ->leftJoin('users as assigned', 'assigned.id', '=', 'tickets.user2_id')
            ->where('tickets.company_id', auth()->user()->current_company_id)
            ->select([
                'tickets.*',
                'people.name as person_name',
                'locations.name as location_name',
                'category.name as category_name',
                'sub_category.name as sub_category_name',
                'reporter.name as reporter_name',
                'assigned.name as assigned_name',
            ])
            ->take(400)
            ->orderBy('tickets.state')
            ->orderBy('tickets.date', 'desc');
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('date')
            ->add('date2')
            ->add('date3')
            ->add('person_name')
            ->add('location_name')
            ->add('category_name')
            ->add('sub_category_name')
            ->add('text', fn($row) => substr($row->text, 0, 35).'...')
            ->add('state', fn($row) => $row->state == '0' ? 'Pendiente' : ($row->state == '1' ? 'Cerrado' : 'Asignado'))
            ->add('reporter_name')
            ->add('assigned_name');
    }

    public function columns(): array
    {
        return [
            COlumn::make('ID', 'id', 'tickets.id')
                ->searchable()
                ->sortable(),

            Column::make('Fecha', 'date', 'tickets.date')
                ->sortable()
                ->searchable(),

            Column::make('Fecha Esperada', 'date2', 'tickets.date2')
                ->sortable()
                ->searchable(),

            Column::make('Fecha Solución', 'date3', 'tickets.date3')
                ->sortable()
                ->searchable(),

            Column::make('Huesped/Tercero', 'person_name', 'people.name')
                ->sortable()
                ->searchable(),

            Column::make('Locación/Habitación', 'location_name', 'locations.name')
                ->sortable()
                ->searchable(),

            Column::make('Categoría', 'category_name', 'category.name')
                ->sortable()
                ->searchable(),

            Column::make('Sub-Categoría', 'sub_category_name', 'sub_category.name')
                ->sortable()
                ->searchable(),

            Column::make('Descripción', 'text', 'tickets.text')
                ->sortable()
                ->searchable(),

            Column::make('Estado', 'state')
                ->sortable()
                ->searchable(),

            Column::make('Reportó', 'reporter_name', 'reporter.name')
                ->sortable()
                ->searchable(),

            Column::make('Asignado', 'assigned_name', 'assigned.name')
                ->sortable()
                ->searchable(),

            Column::action('')
        ];
    }

    public function filters(): array
    {
        return [
            Filter::inputText('date')->operators(['contains']),
            Filter::inputText('date2')->operators(['contains']),
            Filter::inputText('date3')->operators(['contains']),
            Filter::inputText('person_name')->operators(['contains']),
            Filter::inputText('location_name')->operators(['contains']),
            Filter::inputText('category_name')->operators(['contains']),
            Filter::inputText('sub_category_name')->operators(['contains']),
            Filter::inputText('text')->operators(['contains']),
            Filter::inputText('state')->operators(['contains']),
            Filter::inputText('reporter_name')->operators(['contains']),
            Filter::inputText('assigned_name')->operators(['contains']),
        ];
    }

    public function actionsFromView($row): View
    {
        $buttons = [
            [
                'name' => 'Editar',
                'route' => 'manage-ticket.edit',
                'params' => ['manage_ticket' => $row->id],
                'color' => 'blue',
                'icon' => 'fa fa-edit',
                'type' => 'button',
                'active' => true
            ],
            [
                'name' => 'Eliminar',
                'route' => 'manage-ticket.destroy',
                'params' => ['manage_ticket' => $row->id],
                'color' => 'red',
                'icon' => 'fa fa-trash-alt',
                'type' => 'delete',
                'active' => true
            ],
            [
                'name' => 'Ver',
                'route' => 'ticket.show',
                'params' => ['ticket' => $row->id],
                'color' => 'green',
                'icon' => 'fa fa-eye',
                'type' => 'button',
                'active' => true
            ],
        ];

        return view('components.row-buttons', compact('buttons'));   
    }
}
