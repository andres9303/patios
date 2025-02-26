<?php

namespace App\Livewire\Table\Security;

use App\Models\Security\Menu;
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

final class MenuTable extends PowerGridComponent
{
    public string $tableName = 'lpg-menu-table';

    use WithExport;

    public function setUp(): array
    {
        $this->showCheckBox();

        return [
            PowerGrid::exportable('tabla_menus_'.Carbon::now()->format('Y-m-d'))
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
        return Menu::query()->leftJoin('menus as base', function ($menu) {
                $menu->on('menus.menu_id', '=', 'base.id');
            })->select([
                'menus.*',
                'base.name as base_name',
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
            ->add('route')
            ->add('active')
            ->add('icon')
            ->add('base_name');
    }

    public function columns(): array
    {
        return [
            Column::make('Código', 'code', 'menus.code')
                ->sortable()
                ->searchable(),

            Column::make('Formulario', 'name', 'menus.name')
                ->sortable()
                ->searchable(),

            Column::make('Ruta', 'route', 'menus.route')
                ->sortable()
                ->searchable(),

            Column::make('Menú Activo', 'active', 'menus.active')
                ->sortable()
                ->searchable(),

            Column::make('Icono', 'icon', 'menus.icon')
                ->sortable()
                ->searchable(),

            Column::make('Menú Padre', 'base_name', 'base.name')
                ->sortable()
                ->searchable(),

            Column::action('')
        ];
    }

    public function filters(): array
    {
        return [
            Filter::inputText('code', 'menus.code')->operators(['contains']),
            Filter::inputText('name', 'menus.name')->operators(['contains']),
            Filter::inputText('route', 'menus.route')->operators(['contains']),
            Filter::inputText('active', 'menus.active')->operators(['contains']),
            Filter::inputText('icon', 'menus.icon')->operators(['contains']),
            Filter::inputText('base_name', 'base.name')->operators(['contains']),
        ];
    }

    public function actionsFromView($row): View
    {
        $buttons = [
            [
                'name' => 'Editar',
                'route' => 'menu.edit',
                'params' => ['menu' => $row->id],
                'color' => 'blue',
                'icon' => 'fa fa-edit',
                'type' => 'button',
                'active' => true
            ],
            [
                'name' => 'Eliminar',
                'route' => 'menu.destroy',
                'params' => ['menu' => $row->id],
                'color' => 'red',
                'icon' => 'fa fa-trash-alt',
                'type' => 'delete',
                'active' => true
            ],
        ];

        return view('components.row-buttons', compact('buttons'));   
    }
}
