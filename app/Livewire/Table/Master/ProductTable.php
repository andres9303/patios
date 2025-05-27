<?php

namespace App\Livewire\Table\Master;

use App\Models\Config\Item;
use App\Models\Master\Company;
use App\Models\Master\Product;
use App\Models\Master\Unit;
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

final class ProductTable extends PowerGridComponent
{
    use WithExport;
    private int $category_id = 203;
    private int $type_id = 20701;
    public string $tableName = 'lpg-product-table';

    public function setUp(): array
    {
        $this->showCheckBox();

        return [
            PowerGrid::exportable('Productos_' . Carbon::now()->format('Y-m-d'))
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
        return Product::query()
            ->leftJoin('units', 'units.id', '=', 'products.unit_id')
            ->leftJoin('items', 'items.id', '=', 'products.item_id')
            ->leftJoin('items as areas', 'areas.id', '=', 'products.type')
            ->select([
                'products.*',
                'units.name as unit_name',
                'items.name as item_name',
                'areas.name as area_name',
            ])
            ->orderBy('items.name')
            ->orderBy('products.name');
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('code')
            ->add('name')
            ->add('unit_name')
            ->add('state')
            ->add('isinventory')
            ->add('area_name')
            ->add('item_name');
    }

    public function columns(): array
    {
        return [
            Column::make('Código', 'code', 'products.code')
                ->sortable()
                ->searchable(),

            Column::make('Categoría', 'item_name', 'items.id')
                ->sortable()
                ->searchable(),

            Column::make('Nombre', 'name', 'products.name')
                ->sortable()
                ->searchable(),

            Column::make('Unidad', 'unit_name', 'units.id')
                ->sortable()
                ->searchable(),

            Column::make('Estado', 'state', 'products.state')
                ->toggleable(false, 'Activo', 'Inactivo')
                ->sortable()
                ->searchable(),

            Column::make('En inventario', 'isinventory', 'products.isinventory')
                ->toggleable(false, 'Sí', 'No')
                ->sortable()
                ->searchable(),

            Column::make('Área', 'area_name', 'areas.id')
                ->sortable()
                ->searchable(),

            Column::action('')
        ];
    }

    public function filters(): array
    {
        return [
            Filter::inputText('code', 'products.code')->operators(['contains']),
            Filter::inputText('name', 'products.name')->operators(['contains']),
            Filter::select('unit_name', 'units.id')->dataSource(Unit::where('state', 1)->orderBy('name')->get())->optionLabel('name')->optionValue('id'),
            Filter::inputText('state', 'products.state')->operators(['contains']),
            Filter::inputText('isinventory', 'products.isinventory')->operators(['contains']),
            Filter::select('item_name', 'items.id')->dataSource(Item::where('catalog_id', $this->category_id)->orderBy('name')->get())->optionLabel('name')->optionValue('id'),
            Filter::select('area_name', 'areas.id')->dataSource(Item::where('catalog_id', $this->type_id)->orderBy('name')->get())->optionLabel('name')->optionValue('id'),
        ];
    }

    public function actionsFromView($row): View
    {
        $buttons = [
            [
                'name' => 'Editar',
                'route' => 'product.edit',
                'params' => ['product' => $row->id],
                'color' => 'blue',
                'icon' => 'fa fa-edit',
                'type' => 'button',
                'active' => true
            ],
            [
                'name' => 'Eliminar',
                'route' => 'product.destroy',
                'params' => ['product' => $row->id],
                'color' => 'red',
                'icon' => 'fa fa-trash-alt',
                'type' => 'delete',
                'active' => true
            ],
        ];

        return view('components.row-buttons', compact('buttons'));   
    }
}
