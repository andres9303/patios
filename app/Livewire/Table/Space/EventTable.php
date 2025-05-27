<?php

namespace App\Livewire\Table\Space;

use App\Models\Config\Item;
use App\Models\Config\Variable;
use App\Models\Master\Space;
use App\Models\Space\Event;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Components\SetUp\Exportable;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;

final class EventTable extends PowerGridComponent
{
    public string $tableName = 'lpg-space-event-table';
    private int $eventType = 20803;

    public function setUp(): array
    {
        $this->showCheckBox();

        return [
            PowerGrid::exportable('Eventos_'.Carbon::now()->format('Y-m-d'))
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
        return Event::query()
            ->join('items', 'items.id', '=', 'events.item_id')
            ->join('spaces', 'spaces.id', '=', 'events.space_id')
            ->join('users', 'users.id', '=', 'events.user_id')
            ->select([
                'events.*',
                'items.name as item_name',
                'spaces.name as space_name',
                'users.name as user_name',
            ])
            ->where('events.company_id', Auth::user()->current_company_id)
            ->take(1000)
            ->orderBy('events.created_at', 'desc');
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('date')
            ->add('time')
            ->add('title')
            ->add('text')
            ->add('state')
            ->add('user_name')
            ->add('item_name')
            ->add('space_name')
            ->add('location');
    }

    public function columns(): array
    {
        return [
            Column::make('Espacio', 'space_name', 'spaces.id')
                ->searchable()
                ->sortable(),

            Column::make('Tipo Evento', 'item_name', 'items.id')
                ->searchable()
                ->sortable(),

            Column::make('Fecha', 'date', 'events.date')
                ->searchable()
                ->sortable(),

            Column::make('Hora', 'time', 'events.time')
                ->searchable()
                ->sortable(),

            Column::make('Titulo', 'title', 'events.title')
                ->searchable()
                ->sortable(),

            Column::make('Texto', 'text', 'events.text')
                ->searchable()
                ->sortable(),

            Column::make('Ubicación', 'location', 'events.location')
                ->searchable()
                ->sortable(),

            Column::make('Estado', 'state', 'events.state')
                ->toggleable(false, 'Activo', 'Inactivo')
                ->searchable()
                ->sortable(),

            Column::make('Usuario', 'user_name', 'events.user_id')
                ->searchable()
                ->sortable(),

            Column::action('')
        ];
    }
    
    public function filters(): array
    {
        return [
            Filter::datepicker('date', 'events.date'),
            Filter::inputText('title', 'events.title')->operators(['contains']),
            Filter::inputText('text', 'events.text')->operators(['contains']),
            Filter::inputText('location', 'events.location')->operators(['contains']),
            Filter::select('state', 'events.state')->dataSource([['name' => 'Activo', 'id' => 1], ['name' => 'Inactivo', 'id' => 0]])->optionLabel('name')->optionValue('id'),
            Filter::inputText('user_name', 'users.name')->operators(['contains']),
            Filter::select('item_name', 'items.id')->dataSource(Item::where('catalog_id', $this->eventType)->orderBy('order')->get())->optionLabel('name')->optionValue('id'),
            Filter::select('space_name', 'spaces.id')->dataSource(Space::where('company_id', Auth::user()->current_company_id)->get())->optionLabel('name')->optionValue('id'),
        ];
    }

    public function actionsFromView($row): View
    {
        $eventVariable = Variable::where('cod', 'EVNT')->first();
        $excludedConcepts = Variable::where('variable_id', $eventVariable->id)->pluck('concept')->toArray();

        $buttons = [
            [
                'name' => 'Editar',
                'route' => 'event.edit',
                'params' => ['event' => $row->id],
                'color' => 'blue',
                'icon' => 'fa fa-edit',
                'type' => 'button',
                'active' => !in_array($row->item_id, $excludedConcepts)
            ],
            [
                'name' => 'Eliminar',
                'route' => 'event.destroy',
                'params' => ['event' => $row->id],
                'color' => 'red',
                'icon' => 'fa fa-trash-alt',
                'type' => 'delete',
                'active' => !in_array($row->item_id, $excludedConcepts)
            ],
        ];

        return view('components.row-buttons', compact('buttons'));   
    }

    
}
