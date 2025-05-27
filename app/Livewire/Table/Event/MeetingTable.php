<?php

namespace App\Livewire\Table\Event;

use App\Models\Config\Item;
use App\Models\Doc;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\View\View;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Components\SetUp\Exportable;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\Traits\WithExport;

final class MeetingTable extends PowerGridComponent
{
    use WithExport;
    private int $catalog_id = 70002;
    public string $tableName = 'lpg-meeting-table';

    public function setUp(): array
    {
        $this->showCheckBox();

        return [
            PowerGrid::exportable('Categorias_Actividades_' . Carbon::now()->format('Y-m-d'))
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
            ->join('items as category', 'items.item_id', 'category.id')
            ->where('items.catalog_id', $this->catalog_id)
            ->select([
                'items.*',
                'category.name as category_name'
            ])
            ->orderBy('items.order')
            ->orderBy('items.name');
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('name')
            ->add('text')
            ->add('order')
            ->add('category_name');
    }

    public function columns(): array
    {
        return [
            Column::make('Categoría', 'category_name', 'category.name')
                ->sortable()
                ->searchable(),

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
            Filter::inputText('category_name', 'category.name')->operators(['contains']),
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
                'route' => 'meeting.edit',
                'params' => ['meeting' => $row->id],
                'color' => 'blue',
                'icon' => 'fa fa-edit',
                'type' => 'button',
                'active' => true
            ],
            [
                'name' => 'Eliminar',
                'route' => 'meeting.destroy',
                'params' => ['meeting' => $row->id],
                'color' => 'red',
                'icon' => 'fa fa-trash-alt',
                'type' => 'delete',
                'active' => true
            ],
        ];

        return view('components.row-buttons', compact('buttons'));
    }
}
