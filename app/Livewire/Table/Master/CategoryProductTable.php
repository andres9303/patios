<?php

namespace App\Livewire\Table\Master;

use App\Models\Config\Item;
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

final class CategoryProductTable extends PowerGridComponent
{
    use WithExport;

    public string $tableName = 'lpg-category-product-table';

    public function setUp(): array
    {
        $this->showCheckBox();

        return [
            PowerGrid::exportable('Categorias_Productos_' . Carbon::now()->format('Y-m-d'))
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
            ->where('items.catalog_id', 203)
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
                'route' => 'category-product.edit',
                'params' => ['categories_product' => $row->id],
                'color' => 'blue',
                'icon' => 'fa fa-edit',
                'type' => 'button',
                'active' => true
            ],
            [
                'name' => 'Eliminar',
                'route' => 'category-product.destroy',
                'params' => ['categories_product' => $row->id],
                'color' => 'red',
                'icon' => 'fa fa-trash-alt',
                'type' => 'delete',
                'active' => true
            ],
        ];

        return view('components.row-buttons', compact('buttons'));   
    }
}
