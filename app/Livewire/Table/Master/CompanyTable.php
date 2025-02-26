<?php

namespace App\Livewire\Table\Master;

use App\Models\Master\Company;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\View;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Components\SetUp\Exportable;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\Traits\WithExport;

final class CompanyTable extends PowerGridComponent
{
    public string $tableName = 'lpg-company-table';

    use WithExport;

    public function setUp(): array
    {
        $this->showCheckBox();

        return [
            PowerGrid::exportable('centros_costos_'.Carbon::now()->format('Y-m-d'))
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
        return Company::query()
            ->where('companies.id', '<>', 1);
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('name')
            ->add('state')
            ->add('prefix')
            ->add('address')
            ->add('phone')
            ->add('email')
            ->add('head1')
            ->add('head2')
            ->add('head3')
            ->add('foot1')
            ->add('foot2')
            ->add('foot3');
    }

    public function columns(): array
    {
        return [
            Column::make('CCosto', 'name')
                ->sortable()
                ->searchable(),

            Column::make('Estado', 'state')
                ->sortable()
                ->searchable()
                ->toggleable(false, 'Activo', 'Inactivo'),

            Column::make('Prefijo', 'prefix')
                ->sortable()
                ->searchable(),

            Column::make('Dirección', 'address')
                ->sortable()
                ->searchable(),

            Column::make('Teléfono', 'phone')
                ->sortable()
                ->searchable(),

            Column::make('Email', 'email')
                ->sortable()
                ->searchable(),

            Column::make('Encabezado 1', 'head1')
                ->sortable()
                ->searchable(),

            Column::make('Encabezado 2', 'head2')
                ->sortable()
                ->searchable(),

            Column::make('Encabezado 3', 'head3')
                ->sortable()
                ->searchable(),

            Column::make('Pie 1', 'foot1')
                ->sortable()
                ->searchable(),

            Column::make('Pie 2', 'foot2')
                ->sortable()
                ->searchable(),

            Column::make('Pie 3', 'foot3')
                ->sortable()
                ->searchable(),

            Column::action('')
        ];
    }

    public function filters(): array
    {
        return [
            Filter::inputText('name', 'companies.name')->operators(['contains']),
            Filter::inputText('state', 'companies.state')->operators(['contains']),
            Filter::inputText('prefix', 'companies.prefix')->operators(['contains']),
            Filter::inputText('address', 'companies.address')->operators(['contains']),
            Filter::inputText('phone', 'companies.phone')->operators(['contains']),
            Filter::inputText('email', 'companies.email')->operators(['contains']),
            Filter::inputText('head1', 'companies.head1')->operators(['contains']),
            Filter::inputText('head2', 'companies.head2')->operators(['contains']),
            Filter::inputText('head3', 'companies.head3')->operators(['contains']),
            Filter::inputText('foot1', 'companies.foot1')->operators(['contains']),
            Filter::inputText('foot2', 'companies.foot2')->operators(['contains']),
            Filter::inputText('foot3', 'companies.foot3')->operators(['contains']),
        ];
    }

    public function actionsFromView($row): View
    {
        $buttons = [
            [
                'name' => 'Editar',
                'route' => 'company.edit',
                'params' => ['company' => $row->id],
                'color' => 'blue',
                'icon' => 'fa fa-edit',
                'type' => 'button',
                'active' => true
            ],
            [
                'name' => 'Eliminar',
                'route' => 'company.destroy',
                'params' => ['company' => $row->id],
                'color' => 'red',
                'icon' => 'fa fa-trash-alt',
                'type' => 'delete',
                'active' => true
            ],
        ];

        return view('components.row-buttons', compact('buttons'));   
    }
}
