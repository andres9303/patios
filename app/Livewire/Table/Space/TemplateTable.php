<?php

namespace App\Livewire\Table\Space;

use App\Models\Master\Space;
use App\Models\Space\Template;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Components\SetUp\Exportable;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\Rule;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;

final class TemplateTable extends PowerGridComponent
{
    public string $tableName = 'lpg-space-template-table';

    public function setUp(): array
    {
        $this->showCheckBox();

        return [
            PowerGrid::exportable('Plantillas_'.Carbon::now()->format('Y-m-d'))
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
        return Template::query()
            ->join('spaces', 'spaces.id', '=', 'templates.space_id')
            ->where('templates.company_id', Auth::user()->current_company_id)
            ->select([
                'templates.*',
                'spaces.name as space_name',
            ])
            ->orderBy('spaces.name', 'asc')
            ->orderBy('templates.name', 'asc');
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('name')
            ->add('space_name')
            ->add('description')
            ->add('state');
    }

    public function columns(): array
    {
        return [
            Column::make('Plantilla', 'name', 'templates.name')
                ->sortable()
                ->searchable(),

            Column::make('Espacio', 'space_name', 'spaces.id')
                ->sortable()
                ->searchable(),

            Column::make('Descripción', 'description', 'templates.description')
                ->sortable()
                ->searchable(),

            Column::make('Estado', 'state', 'templates.state')
                ->toggleable(false, 'Activo', 'Inactivo')
                ->sortable()
                ->searchable(),

            Column::action('')
        ];
    }

    public function filters(): array
    {
        return [
            Filter::inputText('name', 'templates.name')->operators(['contains']),
            Filter::select('space_name', 'spaces.id')->dataSource(Space::where('state', 1)->where('company_id', Auth::user()->current_company_id)->orderBy('name')->get())->optionLabel('name')->optionValue('id'),
            Filter::inputText('description', 'templates.description')->operators(['contains']),
            Filter::select('state', 'templates.state')->dataSource([['id' => 1, 'name' => 'Activo'], ['id' => 0, 'name' => 'Inactivo']])->optionLabel('name')->optionValue('id'),
        ];
    }

    public function actionsFromView($row): View
    {
        $buttons = [
            [
                'name' => 'Editar',
                'route' => 'template.edit',
                'params' => ['template' => $row->id],
                'color' => 'blue',
                'icon' => 'fa fa-edit',
                'type' => 'button',
                'active' => true
            ],
            [
                'name' => 'Campos',
                'route' => 'field.index',
                'params' => ['template' => $row->id],
                'color' => 'yellow',
                'icon' => 'fa fa-list',
                'type' => 'button',
                'active' => true
            ],
            [
                'name' => 'Ver',
                'route' => 'template.show',
                'params' => ['template' => $row->id],
                'color' => 'green',
                'icon' => 'fa fa-eye',
                'type' => 'button',
                'active' => true
            ],
            [
                'name' => 'Eliminar',
                'route' => 'template.destroy',
                'params' => ['template' => $row->id],
                'color' => 'red',
                'icon' => 'fa fa-trash',
                'type' => 'button',
                'active' => true
            ]
        ];

        return view('components.row-buttons', compact('buttons'));
    }
}
