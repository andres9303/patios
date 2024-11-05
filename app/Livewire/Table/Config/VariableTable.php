<?php

namespace App\Livewire\Table\Config;

use App\Models\Config\Variable;
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

final class VariableTable extends PowerGridComponent
{
    public string $tableName = 'lpg-variable-table';

    use WithExport;

    public function setUp(): array
    {
        $this->showCheckBox();

        return [
            PowerGrid::exportable('tabla_variables_' . Carbon::now()->format('Y-m-d'))
                ->striped()
                ->type(Exportable::TYPE_XLS, Exportable::TYPE_CSV),
            PowerGrid::header()->showSearchInput(),
            PowerGrid::footer()->showPerPage()->showRecordCount(),
        ];
    }

    public function datasource(): Builder
    {
        return Variable::query()
            ->leftJoin('variables as base', 'base.id', '=', 'variables.variable_id')
            ->select([
                'variables.*',
                'base.name as base_name',
            ]);
    }

    public function relationSearch(): array
    {
        return [
        ];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('cod')
            ->add('name')
            ->add('text')
            ->add('concept')
            ->add('value')
            ->add('base_name');
    }

    public function columns(): array
    {
        return [
            Column::make('Código', 'cod', 'variables.cod')
                ->sortable()
                ->searchable(),

            Column::make('Nombre', 'name', 'variables.name')
                ->sortable()
                ->searchable(),

            Column::make('Texto', 'text', 'variables.text')
                ->sortable()
                ->searchable(),

            Column::make('Concepto', 'concept', 'variables.concept'),

            Column::make('Valor', 'value', 'variables.value')
                ->sortable()
                ->searchable(),

            Column::make('Base', 'base_name', 'base.name')
                ->sortable()
                ->searchable(),

            Column::action(''),
        ];
    }

    public function filters(): array
    {
        return [
            Filter::inputText('cod', 'variables.cod')->operators(['contains']),
            Filter::inputText('name', 'variables.name')->operators(['contains']),
            Filter::inputText('text', 'variables.text')->operators(['contains']),
            Filter::inputText('concept', 'variables.concept')->operators(['contains']),
            Filter::inputText('value', 'variables.value')->operators(['contains']),
            Filter::inputText('base_name', 'base.name')->operators(['contains']),
        ];
    }

    public function actionsFromView($row): View
    {
        $buttons = [
            [
                'name' => 'Editar',
                'route' => 'variable.edit',
                'params' => ['variable' => $row->id],
                'color' => 'blue',
                'icon' => 'fa fa-edit',
                'type' => 'button',
                'active' => true
            ],
            [
                'name' => 'Eliminar',
                'route' => 'variable.destroy',
                'params' => ['variable' => $row->id],
                'color' => 'red',
                'icon' => 'fa fa-trash-alt',
                'type' => 'delete',
                'active' => true
            ],
        ];

        return view('components.row-buttons', compact('buttons'));
    }
}
