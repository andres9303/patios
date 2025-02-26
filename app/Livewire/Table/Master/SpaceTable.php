<?php

namespace App\Livewire\Table\Master;

use App\Models\Master\Space;
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

final class SpaceTable extends PowerGridComponent
{
    use WithExport;

    public string $tableName = 'lpg-space-table';

    public function setUp(): array
    {
        $this->showCheckBox();

        return [
            PowerGrid::exportable('Espacios_' . Carbon::now()->format('Y-m-d'))
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
            ->leftJoin('items as item1', 'item1.id', '=', 'spaces.item_id')
            ->leftJoin('items as item2', 'item2.id', '=', 'spaces.item2_id')
            ->leftJoin('spaces as parent', 'parent.id', '=', 'spaces.space_id')
            ->select([
                'spaces.*',
                'item1.name as item_name',
                'item2.name as item2_name',
                'parent.name as parent_name',
            ])
            ->orderby('parent.name')
            ->orderBy('spaces.order')
            ->orderBy('item1.name')
            ->orderBy('spaces.name');
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
            ->add('order')
            ->add('state')
            ->add('item_name')
            ->add('item2_name')
            ->add('cant')
            ->add('parent_name');
    }

    public function columns(): array
    {
        return [
            Column::make('Espacio Padre', 'parent_name', 'parent.name')
                ->sortable()
                ->searchable(),

            Column::make('Categoría', 'item_name', 'item1.name')
                ->sortable()
                ->searchable(),

            Column::make('Nombre', 'name', 'spaces.name')
                ->sortable()
                ->searchable(),

            Column::make('Descripción', 'text', 'spaces.text')
                ->sortable()
                ->searchable(),

            Column::make('Orden', 'order', 'spaces.order')
                ->sortable()
                ->searchable(),

            Column::make('Estado', 'state', 'spaces.state')
                ->toggleable(false, 'Activo', 'Inactivo')
                ->sortable()
                ->searchable(),

            Column::make('Clase', 'item2_name', 'item2.name')
                ->sortable()
                ->searchable(),

            Column::make('Capacidad instalada', 'cant', 'spaces.cant')
                ->sortable()
                ->searchable(),

            Column::action('')
        ];
    }

    public function filters(): array
    {
        return [
            Filter::inputText('name', 'spaces.name')->operators(['contains']),
            Filter::inputText('text', 'spaces.text')->operators(['contains']),
            Filter::inputText('order', 'spaces.order')->operators(['contains']),
            Filter::inputText('state', 'spaces.state')->operators(['contains']),
            Filter::inputText('item_name', 'item1.name')->operators(['contains']),
            Filter::inputText('item2_name', 'item2.name')->operators(['contains']),
            Filter::inputText('cant', 'spaces.cant')->operators(['contains']),
            Filter::inputText('parent_name', 'parent.name')->operators(['contains']),
        ];
    }

    public function actionsFromView($row): View
    {
        $buttons = [
            [
                'name' => 'Editar',
                'route' => 'space.edit',
                'params' => ['space' => $row->id],
                'color' => 'blue',
                'icon' => 'fa fa-edit',
                'type' => 'button',
                'active' => true
            ],
            [
                'name' => 'Eliminar',
                'route' => 'space.destroy',
                'params' => ['space' => $row->id],
                'color' => 'red',
                'icon' => 'fa fa-trash-alt',
                'type' => 'delete',
                'active' => true
            ],
        ];

        return view('components.row-buttons', compact('buttons'));   
    }
}
