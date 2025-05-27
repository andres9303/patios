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

final class KardexTable extends PowerGridComponent
{
    use WithExport;
    public string $primaryKey = 'products.id';
    public string $sortField = 'products.id';
    private $menuIdsNot = [505, 506];
    private $menuIdsAdjust = [504, 507];
    public $start_date;
    public $end_date;

    public string $tableName = 'lpg-kardex-table';

    public function setUp(): array
    {
        $this->showCheckBox();

        return [
            PowerGrid::exportable('Kardex_' . Carbon::now()->format('Y-m-d'))
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
            ->leftJoin('items', 'items.id', '=', 'products.item_id')
            ->leftJoin('items as areas', 'areas.id', '=', 'products.type')
            ->leftJoin('mvtos', function($join) {
                $join->on('mvtos.product2_id', '=', 'products.id')
                    ->join('docs', 'docs.id', '=', 'mvtos.doc_id')
                    ->where('mvtos.cant2', '<>', 0)
                    ->where('docs.state', 1)
                    ->where('mvtos.state', 1)
                    ->where('docs.company_id', Auth::user()->current_company_id)
                    ->whereNotIn('docs.menu_id', $this->menuIdsNot)
                    ->where('docs.date', '<', $this->start_date);
            })
            ->leftJoin('units', 'units.id', '=', 'products.unit_id')
            ->where('products.isinventory', 1)
            ->where('products.state', 1)
            ->whereHas('companies', function ($query) {
                $query->where('company_id', Auth::user()->current_company_id);
            })
            ->select([
                'items.name as category_name',
                'areas.name as area_name',
                'products.id',
                'products.name as product_name',
                'units.name as unit_name',
                DB::raw("SUM(mvtos.cant2) as cant"),
                DB::raw("(SELECT SUM(m.cant2) FROM mvtos AS m
                    JOIN docs AS d ON d.id = m.doc_id
                    WHERE m.cant2 > 0
                    AND d.state = 1
                    AND m.state = 1
                    AND d.menu_id NOT IN (" . implode(',', $this->menuIdsAdjust) . ")
                    AND d.menu_id NOT IN (" . implode(',', $this->menuIdsNot) . ")
                    AND m.product2_id = products.id
                    AND m.unit2_id = units.id
                    AND d.company_id = " . Auth::user()->current_company_id . "
                    AND d.date >= '" . $this->start_date . "'
                    AND d.date <= '" . $this->end_date . "') as cant_in"),
                DB::raw("(SELECT SUM(m.cant2) FROM mvtos AS m
                    JOIN docs AS d ON d.id = m.doc_id
                    WHERE d.state = 1
                    AND m.state = 1
                    AND d.menu_id IN (" . implode(',', $this->menuIdsAdjust) . ")
                    AND d.menu_id NOT IN (" . implode(',', $this->menuIdsNot) . ")
                    AND m.product2_id = products.id
                    AND m.unit2_id = units.id
                    AND d.company_id = " . Auth::user()->current_company_id . "
                    AND d.date >= '" . $this->start_date . "'
                    AND d.date <= '" . $this->end_date . "') as cant_adjust"),
                DB::raw("(SELECT SUM(m.cant2) FROM mvtos AS m
                    JOIN docs AS d ON d.id = m.doc_id
                    WHERE m.cant2 < 0
                    AND d.state = 1
                    AND m.state = 1
                    AND d.menu_id NOT IN (" . implode(',', $this->menuIdsAdjust) . ")
                    AND d.menu_id NOT IN (" . implode(',', $this->menuIdsNot) . ")
                    AND m.product2_id = products.id
                    AND m.unit2_id = units.id
                    AND d.company_id = " . Auth::user()->current_company_id . "
                    AND d.date >= '" . $this->start_date . "'
                    AND d.date <= '" . $this->end_date . "') as cant_out"),
                DB::raw("SUM(mvtos.valuet2) as value"),
                DB::raw("(SELECT SUM(m.valuet2) FROM mvtos AS m
                    JOIN docs AS d ON d.id = m.doc_id
                    WHERE m.valuet2 > 0
                    AND d.state = 1
                    AND m.state = 1
                    AND d.menu_id NOT IN (" . implode(',', $this->menuIdsAdjust) . ")
                    AND d.menu_id NOT IN (" . implode(',', $this->menuIdsNot) . ")
                    AND m.product2_id = products.id
                    AND m.unit2_id = units.id
                    AND d.company_id = " . Auth::user()->current_company_id . "
                    AND d.date >= '" . $this->start_date . "'
                    AND d.date <= '" . $this->end_date . "') as value_in"),
                DB::raw("(SELECT SUM(m.valuet2) FROM mvtos AS m
                    JOIN docs AS d ON d.id = m.doc_id
                    WHERE d.state = 1
                    AND m.state = 1
                    AND d.menu_id IN (" . implode(',', $this->menuIdsAdjust) . ")
                    AND m.product2_id = products.id
                    AND m.unit2_id = units.id
                    AND d.company_id = " . Auth::user()->current_company_id . "
                    AND d.date >= '" . $this->start_date . "'
                    AND d.date <= '" . $this->end_date . "') as value_adjust"),
                DB::raw("(SELECT SUM(m.valuet2) FROM mvtos AS m
                    JOIN docs AS d ON d.id = m.doc_id
                    WHERE m.valuet2 < 0
                    AND d.state = 1
                    AND m.state = 1
                    AND d.menu_id NOT IN (" . implode(',', $this->menuIdsAdjust) . ")
                    AND d.menu_id NOT IN (" . implode(',', $this->menuIdsNot) . ")
                    AND m.product2_id = products.id
                    AND m.unit2_id = units.id
                    AND d.company_id = " . Auth::user()->current_company_id . "
                    AND d.date >= '" . $this->start_date . "'
                    AND d.date <= '" . $this->end_date . "') as value_out"),
            ])
            ->groupBy('products.id', 'units.id', 'items.name', 'products.name', 'units.name', 'areas.name')
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
            ->add('cant_in')
            ->add('cant_in_format', fn ($row) => number_format($row->cant_in, 2))
            ->add('cant_adjust')
            ->add('cant_adjust_format', fn ($row) => number_format($row->cant_adjust, 2))
            ->add('cant_out')
            ->add('cant_out_format', fn ($row) => number_format($row->cant_out, 2))
            ->add('cant_total', fn ($row) => $row->cant + $row->cant_in + $row->cant_out + $row->cant_adjust)
            ->add('cant_total_format', fn ($row) => number_format($row->cant + $row->cant_in + $row->cant_out + $row->cant_adjust, 2))
            ->add('value')
            ->add('value_format', fn ($row) => number_format($row->value))
            ->add('value_in')
            ->add('value_in_format', fn ($row) => number_format($row->value_in))
            ->add('value_adjust')
            ->add('value_adjust_format', fn ($row) => number_format($row->value_adjust))
            ->add('value_out')
            ->add('value_out_format', fn ($row) => number_format($row->value_out))
            ->add('value_total', fn ($row) => $row->value + $row->value_in + $row->value_out + $row->value_adjust)
            ->add('value_total_format', fn ($row) => number_format($row->value + $row->value_in + $row->value_out + $row->value_adjust));
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

            Column::make('Cant. Inicial', 'cant_format')
                ->sortable()
                ->searchable()
                ->visibleInExport(false),

            Column::make('Cant. Inicial', 'cant')
                ->visibleInExport(true)
                ->hidden(),

            Column::make('Cant. Entradas', 'cant_in_format')
                ->sortable()
                ->searchable()
                ->visibleInExport(false),

            Column::make('Cant. Entradas', 'cant_in')
                ->visibleInExport(true)
                ->hidden(),

            Column::make('Cant. Ajustes', 'cant_adjust_format')
                ->sortable()
                ->searchable()
                ->visibleInExport(false),

            Column::make('Cant. Ajustes', 'cant_adjust')
                ->visibleInExport(true)
                ->hidden(),

            Column::make('Cant. Salidas', 'cant_out_format')
                ->sortable()
                ->searchable()
                ->visibleInExport(false),

            Column::make('Cant. Salidas', 'cant_out')
                ->visibleInExport(true)
                ->hidden(),

            Column::make('Cant. Final', 'cant_total_format')
                ->sortable()
                ->searchable()
                ->visibleInExport(false),

            Column::make('Cant. Final', 'cant_total')
                ->visibleInExport(true)
                ->hidden(),

            Column::make('Valor Inicial', 'value_format')
                ->sortable()
                ->searchable()
                ->visibleInExport(false),

            Column::make('Valor Inicial', 'value')
                ->visibleInExport(true)
                ->hidden(),

            Column::make('Valor Entradas', 'value_in_format')
                ->sortable()
                ->searchable()
                ->visibleInExport(false),

            Column::make('Valor Entradas', 'value_in')
                ->visibleInExport(true)
                ->hidden(),

            Column::make('Valor Ajustes', 'value_adjust_format')
                ->sortable()
                ->searchable()
                ->visibleInExport(false),

            Column::make('Valor Ajustes', 'value_adjust')
                ->visibleInExport(true)
                ->hidden(),

            Column::make('Valor Salidas', 'value_out_format')
                ->sortable()
                ->searchable()
                ->visibleInExport(false),

            Column::make('Valor Salidas', 'value_out')
                ->visibleInExport(true)
                ->hidden(),

            Column::make('Valor Final', 'value_total_format')
                ->sortable()
                ->searchable()
                ->visibleInExport(false),

            Column::make('Valor Final', 'value_total')
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
