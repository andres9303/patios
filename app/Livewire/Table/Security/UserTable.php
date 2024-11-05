<?php

namespace App\Livewire\Table\Security;

use App\Models\User;
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

final class UserTable extends PowerGridComponent
{
    public string $tableName = 'lpg-user-table';

    use WithExport;

    public function setUp(): array
    {
        $this->showCheckBox();

        return [
            PowerGrid::exportable('tabla_usuarios_'.Carbon::now()->format('Y-m-d'))
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
        return User::query();
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
            ->add('username')
            ->add('email')
            ->add('created_at');
    }

    public function columns(): array
    {
        return [
            Column::make('NOMBRE USUARIO', 'name')
                ->sortable()
                ->searchable(),

            Column::make('LOGIN', 'username')
                ->sortable()
                ->searchable(),

            Column::make('EMAIL', 'email')
                ->sortable()
                ->searchable(),

            Column::action('')
        ];
    }

    public function filters(): array
    {
        return [
            Filter::inputText('name')->operators(['contains']),
            Filter::inputText('username')->operators(['contains']),
            Filter::inputText('email')->operators(['contains']),
        ];
    }

    public function actionsFromView($row): View
    {
        $buttons = [
            [
                'name' => 'Editar',
                'route' => 'user.edit',
                'params' => ['user' => $row->id],
                'color' => 'blue',
                'icon' => 'fa fa-edit',
                'type' => 'button',
                'active' => true
            ],
            [
                'name' => 'Cambiar contraseña',
                'route' => 'user.editPassword',
                'params' => ['user' => $row->id],
                'color' => 'green',
                'icon' => 'fas fa-key',
                'type' => 'button',
                'active' => true
            ],
            [
                'name' => 'Grupos',
                'route' => 'user.role.index',
                'params' => ['user' => $row->id],
                'color' => 'yellow',
                'icon' => 'fas fa-lock',
                'type' => 'button',
                'active' => true
            ],
            [
                'name' => 'Eliminar',
                'route' => 'user.destroy',
                'params' => ['user' => $row->id],
                'color' => 'red',
                'icon' => 'fa fa-trash-alt',
                'type' => 'delete',
                'active' => $row->id <> auth()->id()
            ],
        ];

        return view('components.row-buttons', compact('buttons'));   
    }
}
