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

final class PermissionTable extends PowerGridComponent
{
    public string $tableName = 'lpg-permission-table';

    use WithExport;

    public function setUp(): array
    {
        $this->showCheckBox();

        return [
            PowerGrid::exportable('tabla_permisos_' . Carbon::now()->format('Y-m-d'))
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
            ->join('permission_role', 'roles.id', '=', 'permission_role.role_id')
            ->join('menus', 'permission_role.menu_id', '=', 'menus.id')
            ->join('permissions', 'permission_role.permission_id', '=', 'permissions.id')
            ->select('roles.id', 
                'roles.name as name_role', 
                'permission_role.menu_id',
                'menus.name as menu_text', 
                'permission_role.permission_id',
                'permissions.name as name_permission'
            );
    }

    public function relationSearch(): array
    {
        return [
            'permissions' => [
                'name',
            ],
            'menus' => [
                'name',
            ],
        ];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('name_role')
            ->add('menu_text')
            ->add('name_permission');
    }

    public function columns(): array
    {
        return [
            Column::make('GRUPO', 'name_role')
                ->sortable()
                ->searchable(),

            Column::make('FORMULARIO', 'menu_text')
                ->sortable()
                ->searchable(),

            Column::make('PERMISO', 'name_permission')
                ->sortable()
                ->searchable(),

            Column::action('')
        ];
    }

    public function filters(): array
    {
        return [
            Filter::inputText('name_role', 'roles.name')->operators(['contains']),
            Filter::inputText('menu_text', 'menus.name')->operators(['contains']),
            Filter::inputText('name_permission', 'permissions.name')->operators(['contains']),
        ];
    }

    public function actionsFromView($row): View
    {
        $buttons = [
            [
                'name' => 'Eliminar',
                'route' => 'permission.destroy',
                'params' => ['role' => $row->id, 'menu' => $row->menu_id, 'permission' => $row->permission_id],
                'color' => 'red',
                'icon' => 'fa fa-trash-alt',
                'type' => 'delete',
                'active' => true
            ],
        ];

        return view('components.row-buttons', compact('buttons'));
    }
}
