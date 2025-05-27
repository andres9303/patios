<?php

namespace App\Livewire\Table\Space;

use App\Models\Master\Space;
use App\Models\Space\CheckList;
use App\Models\Space\Template;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Components\SetUp\Exportable;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;

final class ChecklistTable extends PowerGridComponent
{
    public string $tableName = 'lpg-space-checklist-table';

    public function setUp(): array
    {
        $this->showCheckBox();

        return [
            PowerGrid::exportable('CheckList_'.Carbon::now()->format('Y-m-d'))
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
        return CheckList::query()
            ->join('templates', 'templates.id', '=', 'check_lists.template_id')
            ->join('spaces', 'spaces.id', '=', 'check_lists.space_id')
            ->join('users', 'users.id', '=', 'check_lists.user_id')
            ->select([
                'check_lists.*',
                'templates.id as template_id',
                'templates.name as template_name',
                'spaces.name as space_name',
                'users.name as user_name',
            ])
            ->where('check_lists.company_id', Auth::user()->current_company_id)
            ->take(1000)
            ->orderBy('check_lists.created_at', 'desc');
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('template_name')
            ->add('space_name')
            ->add('user_name')
            ->add('date', fn($row) => Carbon::parse($row->date)->format('Y-m-d'))
            ->add('state')
            ->add('created_at');
    }

    public function columns(): array
    {
        return [
            Column::make('CheckList', 'id', 'check_lists.id')
                ->searchable()
                ->sortable(),

            Column::make('Plantilla', 'template_name', 'templates.id')
                ->searchable()
                ->sortable(),

            Column::make('Espacio', 'space_name', 'spaces.id')
                ->searchable()
                ->sortable(),

            Column::make('Usuario', 'user_name', 'users.id')
                ->searchable()
                ->sortable(),

            Column::make('Fecha', 'date', 'check_lists.date')
                ->searchable()
                ->sortable(),

            Column::make('Estado', 'state', 'check_lists.state')
                ->toggleable(false, 'Activo', 'Inactivo')
                ->searchable()
                ->sortable(),

            Column::make('Creado', 'created_at', 'check_lists.created_at')
                ->searchable()
                ->sortable(),

            Column::action('')
        ];
    }

    public function filters(): array
    {
        return [
            Filter::datepicker('date', 'check_lists.date'),
            Filter::select('state', 'check_lists.state')->dataSource([['name' => 'Activo', 'id' => 1], ['name' => 'Inactivo', 'id' => 0]])->optionLabel('name')->optionValue('id'),
            Filter::inputText('user_name', 'users.name')->operators(['contains']),
            Filter::select('template_name', 'templates.id')->dataSource(Template::where('company_id', Auth::user()->current_company_id)->get())->optionLabel('name')->optionValue('id'),
            Filter::select('space_name', 'spaces.id')->dataSource(Space::where('company_id', Auth::user()->current_company_id)->get())->optionLabel('name')->optionValue('id'),
            Filter::datepicker('created_at', 'check_lists.created_at'),
        ];
    }

    public function actionsFromView($row): View
    {
        $buttons = [
            [
                'name' => 'Editar',
                'route' => 'checklist.edit',
                'params' => ['template' => $row->template_id, 'checklist' => $row->id],
                'color' => 'blue',
                'icon' => 'fa fa-edit',
                'type' => 'button',
                'active' => true
            ],
            [
                'name' => 'Eliminar',
                'route' => 'checklist.destroy',
                'params' => ['template' => $row->template_id, 'checklist' => $row->id],
                'color' => 'red',
                'icon' => 'fa fa-trash-alt',
                'type' => 'delete',
                'active' => $row->state == 1
            ],
        ];

        return view('components.row-buttons', compact('buttons'));
    }
}
