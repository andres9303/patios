<?php

namespace App\Livewire\Table\Master;

use App\Models\Master\Unit;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\View;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Components\SetUp\Exportable;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\Traits\WithExport;

final class UnitTable extends PowerGridComponent
{
    use WithExport;

    public string $tableName = 'lpg-unit-table';

    public function setUp(): array
    {
        $this->showCheckBox();

        return [
            PowerGrid::exportable('Unidades_' . Carbon::now()->format('Y-m-d'))
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
        return Unit::query()
            ->leftJoin('units as parent', 'parent.id', '=', 'units.unit_id')
            ->select([
                'units.*',
                'parent.name as parent_name',
            ])
            ->orderBy('parent.name');
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('name')
            ->add('unit')
            ->add('time')
            ->add('mass')
            ->add('longitude')
            ->add('parent_name')
            ->add('factor')
            ->add('state');
    }

    public function columns(): array
    {
        return [
            Column::make('Nombre', 'name', 'units.name')
                ->sortable()
                ->searchable(),

            Column::make('Unidad', 'unit', 'units.unit')
                ->sortable()
                ->searchable(),

            Column::make('Tiempo', 'time', 'units.time')
                ->sortable()
                ->searchable(),

            Column::make('Masa', 'mass', 'units.mass')
                ->sortable()
                ->searchable(),

            Column::make('Longitud', 'longitude', 'units.longitude')
                ->sortable()
                ->searchable(),

            Column::make('Unidad Padre', 'parent_name', 'parent.name')
                ->sortable()
                ->searchable(),

            Column::make('Factor', 'factor', 'units.factor')
                ->sortable()
                ->searchable(),

            Column::make('Estado', 'state', 'units.state')
                ->toggleable(false, 'Activo', 'Inactivo')
                ->sortable()
                ->searchable(),

            Column::action('')
        ];
    }

    public function filters(): array
    {
        return [
            Filter::inputText('name', 'units.name')->operators(['contains']),
            Filter::inputText('unit', 'units.unit')->operators(['contains']),
            Filter::inputText('time', 'units.time')->operators(['contains']),
            Filter::inputText('mass', 'units.mass')->operators(['contains']),
            Filter::inputText('longitude', 'units.longitude')->operators(['contains']),
            Filter::inputText('parent_name', 'parent.name')->operators(['contains']),
            Filter::inputText('factor', 'units.factor')->operators(['contains']),
            Filter::inputText('state', 'units.state')->operators(['contains']),
        ];
    }

    public function actionsFromView($row): View
    {
        $buttons = [
            [
                'name' => 'Editar',
                'route' => 'unit.edit',
                'params' => ['unit' => $row->id],
                'color' => 'blue',
                'icon' => 'fa fa-edit',
                'type' => 'button',
                'active' => true
            ],
            [
                'name' => 'Eliminar',
                'route' => 'unit.destroy',
                'params' => ['unit' => $row->id],
                'color' => 'red',
                'icon' => 'fa fa-trash-alt',
                'type' => 'delete',
                'active' => true
            ],
        ];

        return view('components.row-buttons', compact('buttons'));   
    }
}
