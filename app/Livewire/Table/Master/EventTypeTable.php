<?php

namespace App\Livewire\Table\Master;

use App\Models\Config\Item;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\View\View;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Components\SetUp\Exportable;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;

final class EventTypeTable extends PowerGridComponent
{
    public int $catalog_id = 20803;
    public string $tableName = 'lpg-event-type-table';

    public function setUp(): array
    {
        $this->showCheckBox();

        return [
            PowerGrid::exportable('Tipos de Eventos_' . Carbon::now()->format('Y-m-d'))
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
        return Item::query()
            ->where('items.catalog_id', $this->catalog_id)
            ->select([
                'items.*'
            ])
            ->orderBy('items.order')
            ->orderBy('items.name');
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('name')
            ->add('text')
            ->add('order');
    }

    public function columns(): array
    {
        return [
            Column::make('Nombre', 'name', 'items.name')
                ->sortable()
                ->searchable(),

            Column::make('Descripción', 'text', 'items.text')
                ->sortable()
                ->searchable(),

            Column::make('Orden', 'order', 'items.order')
                ->sortable()
                ->searchable(),

            Column::action('')
        ];
    }

    public function filters(): array
    {
        return [
            Filter::inputText('name', 'items.name')->operators(['contains']),
            Filter::inputText('text', 'items.text')->operators(['contains']),
            Filter::inputText('order', 'items.order')->operators(['contains']),
        ];
    }

    public function actionsFromView($row): View
    {
        $buttons = [
            [
                'name' => 'Editar',
                'route' => 'event-type.edit',
                'params' => ['event_type' => $row->id],
                'color' => 'blue',
                'icon' => 'fa fa-edit',
                'type' => 'button',
                'active' => true
            ],
            [
                'name' => 'Eliminar',
                'route' => 'event-type.destroy',
                'params' => ['event_type' => $row->id],
                'color' => 'red',
                'icon' => 'fa fa-trash-alt',
                'type' => 'delete',
                'active' => true
            ],
        ];

        return view('components.row-buttons', compact('buttons'));
    }
    
}
