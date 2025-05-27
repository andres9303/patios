<?php

namespace App\Livewire\Table\Cost;

use App\Models\Doc;
use App\Models\Master\Person;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Components\SetUp\Exportable;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\Traits\WithExport;

final class OutputTable extends PowerGridComponent
{
    private int $menu_id = 503; 
    use WithExport;

    public string $tableName = 'lpg-output-table';

    public function setUp(): array
    {
        $this->showCheckBox();

        return [
            PowerGrid::exportable('Salidas_' . Carbon::now()->format('Y-m-d'))
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
        return Doc::query()
            ->where('menu_id', $this->menu_id) 
            ->where('company_id', Auth::user()->current_company_id)
            ->leftJoin('people', 'people.id', '=', 'docs.person_id')
            ->select([
                'docs.*',
                'people.name as person_name',
            ])
            ->limit(500)
            ->orderBy('docs.id', 'desc')
            ->orderBy('docs.date', 'desc');
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('code')
            ->add('num')
            ->add('date')
            ->add('person_name')
            ->add('total')
            ->add('total_format', fn ($row) => number_format($row->total))
            ->add('state');
    }

    public function columns(): array
    {
        return [
            Column::make('Código', 'code', 'docs.code')
                ->sortable()
                ->searchable(),

            Column::make('Número', 'num', 'docs.num')
                ->sortable()
                ->searchable(),

            Column::make('Fecha', 'date', 'docs.date')
                ->sortable()
                ->searchable(),

            Column::make('Responsable', 'person_name', 'people.id')
                ->sortable()
                ->searchable(),

            Column::make('Total', 'total_format', 'docs.total')
                ->sortable()
                ->searchable()
                ->visibleInExport(false),

            Column::make('Total', 'total_format', 'docs.total')
                ->visibleInExport(true)
                ->hidden(),

            Column::make('Estado', 'state', 'docs.state')
                ->toggleable(false, 'Activo', 'Inactivo')
                ->sortable()
                ->searchable(),

            Column::action('')
        ];
    }

    public function filters(): array
    {
        return [
            Filter::inputText('code', 'docs.code')->operators(['contains']),
            Filter::inputText('num', 'docs.num')->operators(['contains']),
            Filter::inputText('date', 'docs.date')->operators(['contains']),
            Filter::select('person_name', 'people.id')->dataSource(Person::where('isEmployee', 1)->where('state', 1)->get())->optionLabel('name')->optionValue('id'),
            Filter::inputText('subtotal', 'docs.subtotal')->operators(['contains']),
            Filter::inputText('iva', 'docs.iva')->operators(['contains']),
            Filter::inputText('total', 'docs.total')->operators(['contains']),
            Filter::inputText('state', 'docs.state')->operators(['contains']),
        ];
    }

    public function actionsFromView($row): View
    {
        $buttons = [
            [
                'name' => 'Editar',
                'route' => 'output.edit',
                'params' => ['output' => $row->id],
                'color' => 'blue',
                'icon' => 'fa fa-edit',
                'type' => 'button',
                'active' => true
            ],
            [
                'name' => 'Eliminar',
                'route' => 'output.destroy',
                'params' => ['output' => $row->id],
                'color' => 'red',
                'icon' => 'fa fa-trash-alt',
                'type' => 'delete',
                'active' => true
            ],
            [
                'name' => 'Adjuntos',
                'route' => 'output.attachment.index',
                'params' => ['output' => $row->id],
                'color' => 'yellow',
                'icon' => 'fa fa-paperclip',
                'type' => 'button',
                'active' => true
            ],
        ];

        return view('components.row-buttons', compact('buttons'));   
    }
}
