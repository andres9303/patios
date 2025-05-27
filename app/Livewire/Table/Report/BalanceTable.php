<?php

namespace App\Livewire\Table\Report;

use App\Models\Config\Item;
use App\Models\Doc;
use App\Models\Master\Product;
use App\Models\Master\Unit;
use App\Models\Mvto;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Components\SetUp\Exportable;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\Traits\WithExport;

final class BalanceTable extends PowerGridComponent
{
    private $menuIdsNot = [505, 506];
    
    use WithExport;
    public string $primaryKey = 'products.id';
    public string $sortField = 'products.id';
    public $date;

    public string $tableName = 'lpg-balance-table';

    public function setUp(): array
    {
        $this->showCheckBox();

        return [
            PowerGrid::exportable('Balance_Inventario_' . Carbon::now()->format('Y-m-d'))
                ->striped()
                ->type(Exportable::TYPE_XLS, Exportable::TYPE_CSV),
            PowerGrid::header(),
            PowerGrid::footer()
                ->showPerPage()
                ->showRecordCount(),
        ];
    }

    public function datasource(): Builder
    {
        return Product::query()
            ->leftJoin('mvtos', function($join) {
                $join->on('products.id', '=', 'mvtos.product2_id')
                    ->join('docs', 'docs.id', '=', 'mvtos.doc_id')
                    ->where('mvtos.cant2', '<>', 0)
                    ->where('docs.state', 1)
                    ->where('mvtos.state', 1)
                    ->where('docs.company_id', Auth::user()->current_company_id)
                    ->where('docs.date', '<=', $this->date)
                    ->whereNotIn('docs.menu_id', $this->menuIdsNot);
            })
            ->leftJoin('units', 'units.id', '=', 'products.unit_id')
            ->leftJoin('items', 'items.id', '=', 'products.item_id')
            ->leftJoin('items as areas', 'areas.id', '=', 'products.type')
            ->where('products.state', 1)
            ->where('products.isinventory', 1)
            ->whereHas('companies', function ($query) {
                $query->where('company_id', Auth::user()->current_company_id);
            })
            ->select([
                'items.name as category_name',
                'products.id',
                'products.name as product_name',
                'units.name as unit_name',
                'areas.name as area_name',
                DB::raw('SUM(mvtos.cant2) as cant'),
                DB::raw('(SELECT SUM(m.cant2) 
                    FROM mvtos AS m
                        JOIN docs AS d ON d.id = m.doc_id
                    WHERE d.state = 1
                    AND m.state = 1
                    AND d.menu_id IN (' . implode(',', $this->menuIdsNot) . ')
                    AND m.product2_id = products.id
                    AND m.unit2_id = units.id
                    AND d.company_id = ' . Auth::user()->current_company_id . '
                    AND d.date <= "' . $this->date . '") as cant_assign'),
                DB::raw('SUM(mvtos.valuet2) as valor'),
            ])
            ->groupBy('products.id', 'items.name', 'products.name', 'units.id', 'units.name', 'areas.name')
            ->orderBy('items.name');
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('category_name')
            ->add('area_name')
            ->add('product_name')
            ->add('unit_name')
            ->add('cant')
            ->add('cant_format', fn ($row) => number_format($row->cant, 2))
            ->add('cant_assign', fn ($row) => $row->cant_assign * -1)
            ->add('cant_assign_format', fn ($row) => number_format($row->cant_assign * -1, 2))
            ->add('cant_diff', fn ($row) => $row->cant + $row->cant_assign)
            ->add('cant_diff_format', fn ($row) => number_format($row->cant + $row->cant_assign, 2))
            ->add('valor')
            ->add('valor_format', fn ($row) => number_format($row->valor));
    }

    public function columns(): array
    {
        return [
            Column::make('Categoría', 'category_name', 'items.name')
                ->sortable()
                ->searchable(),

            Column::make('Área', 'area_name', 'areas.name')
                ->sortable()
                ->searchable(),

            Column::make('Producto', 'product_name', 'products.name')
                ->sortable()
                ->searchable(),

            Column::make('Unidad', 'unit_name', 'units.name')
                ->sortable()
                ->searchable(),

            Column::make('Cant. Total', 'cant_format')
                ->sortable()
                ->searchable()
                ->visibleInExport(false),

            Column::make('Cant. Total', 'cant')
                ->visibleInExport(true)
                ->hidden(),

            Column::make('Valor Inventario', 'valor_format')
                ->sortable()
                ->searchable()
                ->visibleInExport(false),

            Column::make('Valor Inventario', 'valor')
                ->visibleInExport(true)
                ->hidden(),

            Column::make('Cant. Asignada', 'cant_assign_format')
                ->sortable()
                ->searchable()
                ->visibleInExport(false),

            Column::make('Cant. Asignada', 'cant_assign')
                ->visibleInExport(true)
                ->hidden(),

            Column::make('Cant. Almacen', 'cant_diff_format')
                ->sortable()
                ->searchable()
                ->visibleInExport(false),

            Column::make('Cant. Almacen', 'cant_diff')
                ->visibleInExport(true)
                ->hidden(),
        ];
    }

    public function filters(): array
    {
        return [
            Filter::select('category_name', 'items.id')->dataSource(Item::where('catalog_id', 203)->orderBy('order')->get())->optionLabel('name')->optionValue('id'),
            Filter::select('area_name', 'areas.id')->dataSource(Item::where('catalog_id', 20701)->orderBy('order')->get())->optionLabel('name')->optionValue('id'),
            Filter::select('product_name', 'products.id')->dataSource(Product::where('isinventory', 1)->where('state', 1)->orderBy('name')->get())->optionLabel('name')->optionValue('id'),
            Filter::select('unit_name', 'units.id')->dataSource(Unit::where('state', 1)->orderBy('name')->get())->optionLabel('name')->optionValue('id'),
        ];
    }
}
