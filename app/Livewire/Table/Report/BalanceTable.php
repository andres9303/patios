<?php

namespace App\Livewire\Table\Report;

use App\Models\Doc;
use App\Models\Mvto;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
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
    use WithExport;
    public string $primaryKey = 'products.id';

    public string $tableName = 'lpg-balance-table';

    public function setUp(): array
    {
        $this->showCheckBox();

        return [
            PowerGrid::exportable('Balance_Inventario_' . Carbon::now()->format('Y-m-d'))
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
        return Mvto::query()
            ->join('docs', 'docs.id', '=', 'mvtos.doc_id')
            ->join('products', 'products.id', '=', 'mvtos.product2_id')
            ->join('units', 'units.id', '=', 'mvtos.unit2_id')
            ->join('items', 'items.id', '=', 'products.item_id')
            ->where('mvtos.cant2', '<>', 0)
            ->where('docs.state', 1)
            ->where('mvtos.state', 1)
            ->where('products.isinventory', 1)
            ->where('docs.company_id', auth()->user()->current_company_id)
            ->select([
                'items.name as category_name',
                'products.id',
                'products.name as product_name',
                'units.name as unit_name',
                DB::raw('SUM(mvtos.cant2) as cant'),
                DB::raw('SUM(mvtos.valuet2) as valor'),
            ])
            ->groupBy('products.id', 'items.name', 'products.name', 'units.name')
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
            ->add('product_name')
            ->add('unit_name')
            ->add('cant')
            ->add('cant_format', fn ($row) => number_format($row->cant))
            ->add('valor')
            ->add('valor_format', fn ($row) => number_format($row->valor));
    }

    public function columns(): array
    {
        return [
            Column::make('Categoría', 'category_name', 'items.name')
                ->sortable()
                ->searchable(),

            Column::make('Producto', 'product_name', 'products.name')
                ->sortable()
                ->searchable(),

            Column::make('Unidad', 'unit_name', 'units.name')
                ->sortable()
                ->searchable(),

            Column::make('Cantidad', 'cant_format')
                ->sortable()
                ->searchable()
                ->visibleInExport(false),

            Column::make('Cantidad', 'cant')
                ->visibleInExport(true)
                ->hidden(),

            Column::make('Valor', 'valor_format')
                ->sortable()
                ->searchable()
                ->visibleInExport(false),

            Column::make('Valor', 'valor')
                ->visibleInExport(true)
                ->hidden(),
        ];
    }

    public function filters(): array
    {
        return [
            Filter::inputText('category_name', 'items.name')->operators(['contains']),
            Filter::inputText('product_name', 'products.name')->operators(['contains']),
            Filter::inputText('unit_name', 'units.name')->operators(['contains']),
            Filter::inputText('cant')->operators(['contains']),
            Filter::inputText('valor')->operators(['contains']),
        ];
    }
}
