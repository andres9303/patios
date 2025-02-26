<?php

namespace App\Livewire\Table\Report;

use App\Models\Doc;
use App\Models\Mvto;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Components\SetUp\Exportable;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\Traits\WithExport;

final class MovementInvTable extends PowerGridComponent
{
    use WithExport;

    public string $tableName = 'lpg-movement-table';
    public string $primaryKey = 'mvtos.id';

    public function setUp(): array
    {
        $this->showCheckBox();

        return [
            PowerGrid::exportable('Movimientos_Inventario_' . Carbon::now()->format('Y-m-d'))
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
            ->join('menus', 'menus.id', '=', 'docs.menu_id')
            ->join('people', 'people.id', '=', 'docs.person_id')
            ->where('mvtos.cant2', '<>', 0) // Filtro para cant2 <> 0
            ->where('docs.state', 1) // Filtro para docs.state = 1
            ->where('mvtos.state', 1) // Filtro para mvtos.state = 1
            ->where('products.isinventory', 1) // Filtro para products.isinventory = 1
            ->where('docs.company_id', auth()->user()->current_company_id) // Filtro
            ->select([
                'mvtos.id',
                'menus.name as menu_name',
                'docs.code as doc_code',
                'docs.num as doc_num',
                'docs.date as doc_date',
                'people.name as person_name',
                'items.name as category_name',
                'products.name as product_name',
                'units.name as unit_name',
                'mvtos.cant2 as cant',
                'mvtos.valueu2 as valueu',
                'mvtos.iva2 as iva',
                'mvtos.valuet2 as valuet',
            ])
            ->orderBy('docs.date', 'desc');
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('menu_name')
            ->add('doc_code')
            ->add('doc_num')
            ->add('doc_date')
            ->add('doc_date_format', fn ($row) => Carbon::parse($row->doc_date)->format('d/m/Y'))
            ->add('person_name')
            ->add('category_name')
            ->add('product_name')
            ->add('unit_name')
            ->add('cant')
            ->add('cant_format', fn ($row) => number_format($row->cant))
            ->add('valueu')
            ->add('valueu_format', fn ($row) => number_format($row->valueu))
            ->add('iva')
            ->add('valuet')
            ->add('valuet_format', fn ($row) => number_format($row->valuet));
    }

    public function columns(): array
    {
        return [
            Column::make('Menú', 'menu_name', 'menus.name')
                ->sortable()
                ->searchable(),

            Column::make('Código Documento', 'doc_code', 'docs.code')
                ->sortable()
                ->searchable(),

            Column::make('Número Documento', 'doc_num', 'docs.num')
                ->sortable()
                ->searchable(),                

            Column::make('Fecha', 'doc_date_format', 'docs.date')
                ->sortable()
                ->searchable()
                ->visibleInExport(false),

            Column::make('Fecha', 'doc_date', 'docs.date')
                ->visibleInExport(true)
                ->hidden(),

            Column::make('Persona', 'person_name', 'people.name')
                ->sortable()
                ->searchable(),

            Column::make('Categoría', 'category_name', 'items.name')
                ->sortable()
                ->searchable(),

            Column::make('Producto', 'product_name', 'products.name')
                ->sortable()
                ->searchable(),

            Column::make('Unidad', 'unit_name', 'units.name')
                ->sortable()
                ->searchable(),

            Column::make('Cantidad', 'cant_format', 'mvtos.cant2')
                ->sortable()
                ->searchable()
                ->visibleInExport(false),

            Column::make('Cantidad', 'cant', 'mvtos.cant2')
                ->visibleInExport(true)
                ->hidden(),

            Column::make('Valor Unitario', 'valueu_format', 'mvtos.valueu2')
                ->sortable()
                ->searchable()
                ->visibleInExport(false),

            Column::make('Valor Unitario', 'valueu', 'mvtos.valueu2')
                ->visibleInExport(true)
                ->hidden(),

            Column::make('IVA', 'iva', 'mvtos.iva')
                ->sortable()
                ->searchable(),

            Column::make('Valor Total', 'valuet_format', 'mvtos.valuet2')
                ->sortable()
                ->searchable()
                ->visibleInExport(false),

            Column::make('Valor Total', 'valuet', 'mvtos.valuet2')
                ->visibleInExport(true)
                ->hidden()
        ];
    }

    public function filters(): array
    {
        return [
            Filter::inputText('menu_name', 'menus.name')->operators(['contains']),
            Filter::inputText('doc_code', 'docs.code')->operators(['contains']),
            Filter::inputText('doc_num', 'docs.num')->operators(['contains']),
            Filter::inputText('person_name', 'people.name')->operators(['contains']),
            Filter::inputText('category_name', 'items.name')->operators(['contains']),
            Filter::inputText('product_name', 'products.name')->operators(['contains']),
            Filter::inputText('unit_name', 'units.name')->operators(['contains']),
            Filter::inputText('cant', 'mvtos.cant2')->operators(['contains']),
            Filter::inputText('valueu', 'mvtos.valueu2')->operators(['contains']),
            Filter::inputText('iva', 'mvtos.iva2')->operators(['contains']),
            Filter::inputText('valuet', 'mvtos.valuet2')->operators(['contains']),
        ];
    }
}
