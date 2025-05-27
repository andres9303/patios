<?php

namespace App\Livewire\Table\Config;

use App\Models\Config\Catalog;
use App\Models\Config\Item;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Components\SetUp\Exportable;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\Traits\WithExport;

final class ItemTable extends PowerGridComponent
{
    use WithExport;

    public string $tableName = 'lpg-item-table';

    public function setUp(): array
    {
        $this->showCheckBox();

        return [
            PowerGrid::exportable('items_'.Carbon::now()->format('Y-m-d'))
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
            ->join('catalogs', 'catalogs.id', '=', 'items.catalog_id')
            ->leftJoin('items as base', 'base.id', '=', 'items.item_id')
            ->select([
                'items.*',
                'base.text as base_name',
                'catalogs.name as catalog_name',
            ]);
    }

    public function relationSearch(): array
    {
        return [
            'catalog' => [
                'name',
            ],
        ];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('catalog_name')
            ->add('name')
            ->add('base_name')
            ->add('text')
            ->add('factor');
    }

    public function columns(): array
    {
        return [
            Column::make('Lista', 'catalog_name', 'catalogs.id')
                ->sortable()
                ->searchable(),

            Column::make('Item', 'name', 'items.name')
                ->sortable()
                ->searchable(),

            Column::make('Base', 'base_name', 'base.name')
                ->sortable()
                ->searchable(),

            Column::make('Texto', 'text', 'items.text')
                ->sortable()
                ->searchable(),

            Column::make('Factor', 'factor', 'items.factor'),

            Column::action('') // Para acciones como editar o eliminar
        ];
    }

    public function filters(): array
    {
        return [
            Filter::select('catalog_name', 'catalogs.id')->dataSource(Catalog::orderBy('name')->get())->optionLabel('name')->optionValue('id'),
            Filter::inputText('name', 'items.name')->operators(['contains']),
            Filter::inputText('base_name', 'base.name')->operators(['contains']),
            Filter::inputText('text', 'items.text')->operators(['contains']),
        ];
    }

    public function actionsFromView($row): \Illuminate\View\View
    {
        $buttons = [
            [
                'name' => 'Editar',
                'route' => 'list.edit',
                'params' => ['list' => $row->id],
                'color' => 'blue',
                'icon' => 'fa fa-edit',
                'type' => 'button',
                'active' => true
            ],
            [
                'name' => 'Eliminar',
                'route' => 'list.destroy',
                'params' => ['list' => $row->id],
                'color' => 'red',
                'icon' => 'fa fa-trash-alt',
                'type' => 'delete',
                'active' => true
            ],
        ];

        return view('components.row-buttons', compact('buttons'));
    }
}
