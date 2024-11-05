<?php

namespace App\Livewire\Table\Security;

use App\Models\Security\Role;
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

final class ShortcutTable extends PowerGridComponent
{
    public string $tableName = 'lpg-shortcut-table';

    use WithExport;

    public function setUp(): array
    {
        $this->showCheckBox();

        return [
            PowerGrid::exportable('tabla_accesos_directos_' . Carbon::now()->format('Y-m-d'))
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
        return Role::query()
            ->join('menu_role', 'roles.id', '=', 'menu_role.role_id')
            ->join('menus', 'menu_role.menu_id', '=', 'menus.id')
            ->select('roles.id', 
                'roles.name as name_role', 
                'menu_role.menu_id',
                'menus.name as menu_text'
            );
    }

    public function relationSearch(): array
    {
        return [
            'menus' => [
                'name',
            ],
        ];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('name_role')
            ->add('menu_text');
    }

    public function columns(): array
    {
        return [
            Column::make('Grupo', 'name_role')
                ->sortable()
                ->searchable(),

            Column::make('Formulario', 'menu_text')
                ->sortable()
                ->searchable(),

            Column::action('') // Acción añadida para la columna de acciones
        ];
    }

    public function filters(): array
    {
        return [
            Filter::inputText('name_role', 'roles.name')->operators(['contains']),
            Filter::inputText('menu_text', 'menus.name')->operators(['contains']),
        ];
    }

    public function actionsFromView($row): View
    {
        $buttons = [
            [
                'name' => 'Eliminar',
                'route' => 'shortcut.destroy',
                'params' => ['role' => $row->id, 'menu' => $row->menu_id],
                'color' => 'red',
                'icon' => 'fa fa-trash-alt',
                'type' => 'delete',
                'active' => true
            ],
        ];

        return view('components.row-buttons', compact('buttons'));
    }
}
