<?php

namespace App\Livewire\Table\Master;

use App\Models\Master\Category;
use App\Models\Master\Company;
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

final class CategoryTable extends PowerGridComponent
{
    public string $tableName = 'lpg-category-table';

    use WithExport;

    public function setUp(): array
    {
        $this->showCheckBox();

        return [
            PowerGrid::exportable('Categorias_'.Carbon::now()->format('Y-m-d'))
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

        return Category::query()
            ->leftJoin('categories as parent', 'parent.id', '=', 'categories.ref_id')
            ->whereIn('categories.company_id', [auth()->user()->current_company_id, $company_all->id])
            ->select([
                'categories.*',
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
            ->add('code')
            ->add('name')
            ->add('text')
            ->add('parent_name')
            ->add('state');
    }

    public function columns(): array
    {
        return [
            Column::make('Código', 'code', 'categories.code')
                ->sortable()
                ->searchable(),

            Column::make('Nombre Categoría', 'name', 'categories.name')
                ->sortable()
                ->searchable(),

            Column::make('Descripción', 'text', 'categories.text')
                ->sortable()
                ->searchable(),

            Column::make('Categoría Padre', 'parent_name', 'parent.name')
                ->sortable()
                ->searchable(),

            Column::make('Estado', 'state', 'categories.state')
                ->toggleable(false, 'Activo', 'Inactivo')
                ->sortable()
                ->searchable(),

            Column::action('')
        ];
    }

    public function filters(): array
    {
        return [
            Filter::inputText('code')->operators(['contains']),
            Filter::inputText('name')->operators(['contains']),
            Filter::inputText('text')->operators(['contains']),
            Filter::inputText('parent_name')->operators(['contains']),
            Filter::inputText('state')->operators(['contains']),
        ];
    }

    public function actionsFromView($row): View
    {
        $buttons = [
            [
                'name' => 'Editar',
                'route' => 'category.edit',
                'params' => ['category' => $row->id],
                'color' => 'blue',
                'icon' => 'fa fa-edit',
                'type' => 'button',
                'active' => true
            ],
            [
                'name' => 'Eliminar',
                'route' => 'category.destroy',
                'params' => ['category' => $row->id],
                'color' => 'red',
                'icon' => 'fa fa-trash-alt',
                'type' => 'delete',
                'active' => true
            ],
        ];

        return view('components.row-buttons', compact('buttons'));   
    }
}
