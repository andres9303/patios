<?php

namespace App\Livewire\Table\Cost;

use App\Models\Doc;
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

final class DirectPurchaseTable extends PowerGridComponent
{
    use WithExport;

    public string $tableName = 'lpg-direct-purchase-table';

    public function setUp(): array
    {
        $this->showCheckBox();

        return [
            PowerGrid::exportable('Compras_' . Carbon::now()->format('Y-m-d'))
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
        return Doc::query()
            ->where('menu_id', 502) // Filtro por menu_id = 502
            ->where('company_id', auth()->user()->current_company_id)
            ->leftJoin('people', 'people.id', '=', 'docs.person_id')
            ->select([
                'docs.*',
                'people.name as person_name',
            ])
            ->limit(500)
            ->orderBy('docs.id', 'desc')
            ->orderBy('docs.date', 'desc');
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('code')
            ->add('num')
            ->add('date')
            ->add('person_name')
            ->add('subtotal')
            ->add('subtotal_format', fn ($row) => number_format($row->subtotal))
            ->add('iva')
            ->add('iva_format', fn ($row) => number_format($row->iva))
            ->add('total')
            ->add('total_format', fn ($row) => number_format($row->total))
            ->add('state');
    }

    public function columns(): array
    {
        return [
            Column::make('Código', 'code', 'docs.code')
                ->sortable()
                ->searchable(),

            Column::make('Número', 'num', 'docs.num')
                ->sortable()
                ->searchable(),

            Column::make('Fecha', 'date', 'docs.date')
                ->sortable()
                ->searchable(),

            Column::make('Persona', 'person_name', 'people.name')
                ->sortable()
                ->searchable(),

            Column::make('Subtotal', 'subtotal_format', 'docs.subtotal')
                ->sortable()
                ->searchable()
                ->visibleInExport(false),

            Column::make('Subtotal', 'subtotal', 'docs.subtotal')
                ->visibleInExport(true)
                ->hidden(),

            Column::make('IVA', 'iva_format', 'docs.iva')
                ->sortable()
                ->searchable()
                ->visibleInExport(false),

            Column::make('IVA', 'iva_format', 'docs.iva')
                ->visibleInExport(true)
                ->hidden(),

            Column::make('Total', 'total_format', 'docs.total')
                ->sortable()
                ->searchable()
                ->visibleInExport(false),

            Column::make('Total', 'total_format', 'docs.total')
                ->visibleInExport(true)
                ->hidden(),

            Column::make('Estado', 'state', 'docs.state')
                ->toggleable(false, 'Activo', 'Inactivo')
                ->sortable()
                ->searchable(),

            Column::action('')
        ];
    }

    public function filters(): array
    {
        return [
            Filter::inputText('code', 'docs.code')->operators(['contains']),
            Filter::inputText('num', 'docs.num')->operators(['contains']),
            Filter::inputText('date', 'docs.date')->operators(['contains']),
            Filter::inputText('person_name', 'people.name')->operators(['contains']),
            Filter::inputText('subtotal', 'docs.subtotal')->operators(['contains']),
            Filter::inputText('iva', 'docs.iva')->operators(['contains']),
            Filter::inputText('total', 'docs.total')->operators(['contains']),
            Filter::inputText('state', 'docs.state')->operators(['contains']),
        ];
    }

    public function actionsFromView($row): View
    {
        $buttons = [
            [
                'name' => 'Editar',
                'route' => 'direct-purchase.edit',
                'params' => ['direct_purchase' => $row->id],
                'color' => 'blue',
                'icon' => 'fa fa-edit',
                'type' => 'button',
                'active' => true
            ],
            [
                'name' => 'Eliminar',
                'route' => 'direct-purchase.destroy',
                'params' => ['direct_purchase' => $row->id],
                'color' => 'red',
                'icon' => 'fa fa-trash-alt',
                'type' => 'delete',
                'active' => true
            ],
        ];

        return view('components.row-buttons', compact('buttons'));   
    }
}
