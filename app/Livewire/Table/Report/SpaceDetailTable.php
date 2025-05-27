<?php

namespace App\Livewire\Table\Report;

use App\Models\Master\Space;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Components\SetUp\Exportable;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\Rule;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

final class SpaceDetailTable extends PowerGridComponent
{
    public string $tableName = 'space-detail-table';
    public string $primaryKey = 'mvtos.id';

    public function setUp(): array
    {
        $this->showCheckBox();

        return [
            PowerGrid::exportable('Detalle_Espacios_' . Carbon::now()->format('Y-m-d'))
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
            ->join('mvtos', 'mvtos.space_id', '=', 'spaces.id')
            ->join('docs', 'docs.id', '=', 'mvtos.doc_id')
            ->join('menus', 'menus.id', '=', 'docs.menu_id')
            ->join('products', 'products.id', '=', 'mvtos.product_id')
            ->join('units', 'units.id', '=', 'mvtos.unit_id')
            ->where('mvtos.cant', '<>', 0)
            ->where('mvtos.costu', '<>', 0)
            ->where('docs.state', 1)
            ->where('mvtos.state', 1)
            ->where('docs.company_id', Auth::user()->current_company_id)
            ->where('spaces.company_id', Auth::user()->current_company_id)
            ->select([
                'mvtos.id',
                'menus.name as menu_name',
                'docs.date as doc_date',
                'docs.code as doc_code',
                'docs.num as doc_num',
                'spaces.name as space_name',
                'products.name as product_name',
                'units.name as unit_name',
                'mvtos.cant',
                'mvtos.costu',
                DB::raw('mvtos.cant * mvtos.costu as cost_total'),
            ])
            ->orderBy('spaces.name');
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('menu_name')
            ->add('doc_date')
            ->add('doc_code')
            ->add('doc_num')
            ->add('space_name')
            ->add('product_name')
            ->add('unit_name')
            ->add('cant')
            ->add('cant_format', fn ($row) => number_format($row->cant))
            ->add('costu')
            ->add('costu_format', fn ($row) => number_format($row->costu))
            ->add('cost_total')
            ->add('cost_total_format', fn ($row) => number_format($row->cost_total));
    }

    public function columns(): array
    {
        return [
            Column::make('Menu', 'menu_name', 'menus.name')
                ->sortable()
                ->searchable(),

            Column::make('Fecha', 'doc_date', 'docs.date')
                ->sortable()
                ->searchable(),

            Column::make('Código doc.', 'doc_code', 'docs.code')
                ->sortable()
                ->searchable(),

            Column::make('Número doc.', 'doc_num', 'docs.num')
                ->sortable()
                ->searchable(),

            Column::make('Espacio', 'space_name', 'spaces.name')
                ->sortable()
                ->searchable(),

            Column::make('Producto', 'product_name', 'products.name')
                ->sortable()
                ->searchable(),

            Column::make('Unidad', 'unit_name', 'units.name')
                ->sortable()
                ->searchable(),

            Column::make('Cant.', 'cant_format')
                ->sortable()
                ->searchable()
                ->visibleInExport(false),

            Column::make('Cant.', 'cant')
                ->visibleInExport(true)
                ->hidden(),

            Column::make('CostoU', 'costu_format')
                ->sortable()
                ->searchable()
                ->visibleInExport(false),

            Column::make('CostoU', 'costu')
                ->visibleInExport(true)
                ->hidden(),

            Column::make('CostoT', 'cost_total_format')
                ->sortable()
                ->searchable()
                ->visibleInExport(false),

            Column::make('CostoT', 'cost_total')
                ->visibleInExport(true)
                ->hidden(),
        ];
    }

    public function filters(): array
    {
        return [
            Filter::inputText('menu_name', 'menus.name')->operators(['contains']),
            Filter::datetimepicker('doc_date', 'docs.date'),
            Filter::inputText('doc_code', 'docs.code')->operators(['contains']),
            Filter::inputText('doc_num', 'docs.num')->operators(['contains']),
            Filter::inputText('space_name', 'spaces.name')->operators(['contains']),
            Filter::inputText('product_name', 'products.name')->operators(['contains']),
            Filter::inputText('unit_name', 'units.name')->operators(['contains']),
        ];
    }
}
