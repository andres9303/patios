<?php

namespace App\Livewire\Table\Ticket;

use App\Models\Config\Item;
use App\Models\Config\Variable;
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

final class Traking2Table extends PowerGridComponent
{
    public string $tableName = 'lpg-tracking2-table';

    use WithExport;

    public function setUp(): array
    {
        $this->showCheckBox();

        return [
            PowerGrid::exportable('Tickets2_'.Carbon::now()->format('Y-m-d'))
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
        $tracking2 = Variable::where('cod', 'TKT_TYPE2')->first()->concept;

        return Ticket::query()
            ->leftJoin('items', 'items.id', '=', 'tickets.item_id')
            ->leftJoin('locations', 'locations.id', '=', 'tickets.location_id')
            ->leftJoin('categories as category', 'category.id', '=', 'tickets.category_id')
            ->leftJoin('categories as sub_category', 'sub_category.id', '=', 'tickets.category2_id')
            ->leftJoin('users as reporter', 'reporter.id', '=', 'tickets.user_id')
            ->leftJoin('users as assigned', 'assigned.id', '=', 'tickets.user2_id')
            ->where('tickets.company_id', auth()->user()->current_company_id)
            ->where('tickets.user2_id', auth()->user()->id)
            ->whereIn('tickets.category2_id', [$tracking2])
            ->select([
                'tickets.*',
                'items.name as priority',
                'locations.name as location_name',
                'category.name as category_name',
                'sub_category.name as sub_category_name',
                'reporter.name as reporter_name',
                'assigned.name as assigned_name',
            ])
            ->take(200)           
            ->orderByRaw("
            CASE 
                WHEN tickets.state = 0 THEN 1
                WHEN tickets.state = 2 THEN 2
                WHEN tickets.state = 1 THEN 3
            END
            ")
            ->orderBy('items.order', 'desc')
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
            ->add('name')
            ->add('location_name')
            ->add('category_name')
            ->add('sub_category_name')
            ->add('priority')
            ->add('text', fn($row) => substr($row->text, 0, 50).'...')
            ->add('state')
            ->add('state_det', fn($row) => $row->state == '0' ? '<div class="bg-red-500 text-white w-full h-full text-center">Pendiente</div>' : ($row->state == '1' ? '<div class="bg-green-500 text-white w-full h-full text-center">Cerrado</div>' : '<div class="bg-blue-500 text-white w-full h-full text-center">Asignado</div>'))
            ->add('reporter_name')
            ->add('assigned_name');
    }

    public function columns(): array
    {
        return [
            COlumn::make('ID', 'id', 'tickets.id')
                ->searchable()
                ->sortable(),

            Column::make('Estado', 'state_det', 'tickets.state')
                ->sortable()
                ->searchable()
                ->visibleInExport(false),

            Column::make('Estado', 'state', 'tickets.state')
                ->visibleInExport(true)
                ->hidden(),

            Column::make('Prioridad', 'priority', 'items.name')
                ->sortable()
                ->searchable(),

            Column::make('Fecha', 'date', 'tickets.date')
                ->sortable()
                ->searchable(),

            Column::make('Fecha Solución', 'date3', 'tickets.date3')
                ->sortable()
                ->searchable(),

            Column::make('Locación/Habitación', 'location_name', 'locations.name')
                ->sortable()
                ->searchable(),

            Column::make('Descripción', 'text', 'tickets.text')
                ->sortable()
                ->searchable(),

            Column::make('Huesped/Tercero', 'name', 'tickets.name')
                ->sortable()
                ->searchable(),            

            Column::make('Categoría', 'category_name', 'category.name')
                ->sortable()
                ->searchable(),

            Column::make('Sub-Categoría', 'sub_category_name', 'sub_category.name')
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
            Filter::datetimepicker('date', 'tickets.'),
            Filter::datetimepicker('date2', 'tickets.date2'),
            Filter::datetimepicker('date3', 'tickets.date3'),
            Filter::inputText('name', 'tickets.name')->operators(['contains']),
            Filter::inputText('location_name', 'locations.name')->operators(['contains']),
            Filter::inputText('category_name', 'category.name')->operators(['contains']),
            Filter::inputText('sub_category_name', 'sub_category.name')->operators(['contains']),
            Filter::select('priority', 'tickets.item_id')->dataSource(Item::where('catalog_id', 3)->orderBy('order')->get())->optionLabel('name')->optionValue('id'),
            Filter::inputText('text', 'tickets.text')->operators(['contains']),
            Filter::select('state_det', 'tickets.state')->dataSource([['name' => 'Pendiente', 'id' => 0], ['name' => 'Cerrado', 'id' => 1], ['name' => 'Asignado', 'id' => 2]])->optionLabel('name')->optionValue('id'),
            Filter::inputText('reporter_name', 'reporter.name')->operators(['contains']),
            Filter::inputText('assigned_name', 'assigned.name')->operators(['contains']),
        ];
    }

    public function actionsFromView($row): View
    {
        $buttons = [
            [
                'name' => 'Seguimiento',
                'route' => 'resolve-2ticket.create',
                'params' => ['ticket' => $row->id],
                'color' => 'blue',
                'icon' => 'fa fa-plus',
                'type' => 'button',
                'active' => $row->state == 2
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
