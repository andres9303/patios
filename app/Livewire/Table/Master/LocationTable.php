<?php

namespace App\Livewire\Table\Master;

use App\Models\Master\Company;
use App\Models\Master\Location;
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

final class LocationTable extends PowerGridComponent
{
    public string $tableName = 'lpg-location-table';

    use WithExport;

    public function setUp(): array
    {
        $this->showCheckBox();

        return [
            PowerGrid::exportable('Locaciones_'.Carbon::now()->format('Y-m-d'))
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
        $company_all = Company::where('name', 'Todos')->first();

        return Location::query()
            ->leftJoin('locations as parent', 'parent.id', '=', 'locations.ref_id')
            ->whereIn('locations.company_id', [auth()->user()->current_company_id, $company_all->id])
            ->select([
                'locations.*',
                'parent.name as parent_name',
            ]);
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
            ->add('text')
            ->add('parent_name')
            ->add('state');
    }

    public function columns(): array
    {
        return [
            Column::make('Código', 'code', 'locations.code')
                ->sortable()
                ->searchable(),

            Column::make('Nombre Locación', 'name', 'locations.name')
                ->sortable()
                ->searchable(),

            Column::make('Descripción', 'text', 'locations.text')
                ->sortable()
                ->searchable(),

            Column::make('Locación Padre', 'parent_name', 'parent.name')
                ->sortable()
                ->searchable(),

            Column::make('Estado', 'state', 'locations.state')
                ->toggleable(false, 'Activo', 'Inactivo')
                ->sortable()
                ->searchable(),

            Column::action('')
        ];
    }

    public function filters(): array
    {
        return [
            Filter::inputText('code', 'locations.code')->operators(['contains']),
            Filter::inputText('name', 'locations.name')->operators(['contains']),
            Filter::inputText('text', 'locations.text')->operators(['contains']),
            Filter::inputText('parent_name', 'parent.name')->operators(['contains']),
            Filter::inputText('state', 'locations.state')->operators(['contains']),
        ];
    }

    public function actionsFromView($row): View
    {
        $buttons = [
            [
                'name' => 'Editar',
                'route' => 'location.edit',
                'params' => ['location' => $row->id],
                'color' => 'blue',
                'icon' => 'fa fa-edit',
                'type' => 'button',
                'active' => true
            ],
            [
                'name' => 'Eliminar',
                'route' => 'location.destroy',
                'params' => ['location' => $row->id],
                'color' => 'red',
                'icon' => 'fa fa-trash-alt',
                'type' => 'delete',
                'active' => true
            ],
        ];

        return view('components.row-buttons', compact('buttons'));   
    }
}
