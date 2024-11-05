<?php

namespace App\Livewire\Table\Security;

use App\Models\User;
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

final class UserRoleTable extends PowerGridComponent
{
    public string $tableName = 'lpg-user-role-table';
    use WithExport;
    public int $user;

    public function setUp(): array
    {
        $this->showCheckBox();
        $user = User::find($this->user);

        return [
            PowerGrid::exportable('tabla_'.$user->name.'_roles_'.Carbon::now()->format('Y-m-d'))
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
        return User::query()
            ->join('company_user', 'users.id', '=', 'company_user.user_id')
            ->join('roles', 'company_user.role_id', '=', 'roles.id')
            ->join('companies', 'company_user.company_id', '=', 'companies.id')
            ->where('users.id', $this->user)
            ->select('users.id', 
                'roles.name as name_role', 
                'company_user.role_id',
                'companies.name as name_company', 
                'company_user.company_id',
                'users.name as name_user'
            );
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('name_user')
            ->add('name_role')
            ->add('name_company');
    }

    public function columns(): array
    {
        return [
            Column::make('Usuario', 'name_user', 'users.name')
                ->sortable()
                ->searchable(),

            Column::make('Grupos', 'name_role', 'roles.name')
                ->sortable()
                ->searchable(),

            Column::make('Centro de costos', 'name_company', 'companies.name')
                ->sortable()
                ->searchable(),

            Column::action('')
        ];
    }

    public function filters(): array
    {
        return [
            Filter::inputText('name_user', 'users.name')->operators(['contains']),
            Filter::inputText('name_role', 'roles.name')->operators(['contains']),
            Filter::inputText('name_company', 'companies.name')->operators(['contains']),
        ];
    }

    public function actionsFromView($row): View
    {
        $buttons = [
            [
                'name' => 'Eliminar',
                'route' => 'user.role.destroy',
                'params' => ['user' => $row->id, 'role' => $row->role_id, 'company' => $row->company_id],
                'color' => 'red',
                'icon' => 'fa fa-trash-alt',
                'type' => 'delete',
                'active' => true
            ],
        ];

        return view('components.row-buttons', compact('buttons'));   
    }
}
