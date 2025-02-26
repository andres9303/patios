<?php

namespace App\Livewire\Table\Master;

use App\Models\Master\Person;
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

final class PersonTable extends PowerGridComponent
{
    public string $tableName = 'lpg-person-table';
    
    use WithExport;

    public function setUp(): array
    {
        $this->showCheckBox();

        return [
            PowerGrid::exportable('terceros_'.Carbon::now()->format('Y-m-d'))
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
        return Person::query();
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('identification')
            ->add('name')
            ->add('email')
            ->add('phone')
            ->add('address')
            ->add('whatsapp')
            ->add('text')
            ->add('birth_formatted', fn (Person $model) => Carbon::parse($model->birth)->format('d/m/Y'))
            ->add('isClient')
            ->add('isSupplier')
            ->add('isEmployee')
            ->add('state');
    }

    public function columns(): array
    {
        return [
            Column::make('Identificación', 'identification', 'people.identification')
                ->sortable()
                ->searchable(),

            Column::make('Nombre', 'name', 'people.name')
                ->sortable()
                ->searchable(),

            Column::make('Email', 'email', 'people.email')
                ->sortable()
                ->searchable(),

            Column::make('Teléfono', 'phone', 'people.phone')
                ->sortable()
                ->searchable(),

            Column::make('Dirección', 'address', 'people.address')
                ->sortable()
                ->searchable(),

            Column::make('Whatsapp', 'whatsapp', 'people.whatsapp')
                ->sortable()
                ->searchable(),

            Column::make('Observaciones', 'text', 'people.text')
                ->sortable()
                ->searchable(),

            Column::make('Cumpleaños', 'birth_formatted', 'people.birth')
                ->sortable(),

            Column::make('esCliente?', 'isClient', 'people.isClient')
                ->toggleable(false, 'Si', 'No')
                ->sortable()
                ->searchable(),

            Column::make('esProveedor?', 'isSupplier', 'people.isSupplier')
                ->toggleable(false, 'Si', 'No')
                ->sortable()
                ->searchable(),

            Column::make('esEmpleado', 'isEmployee', 'people.isEmployee')
                ->toggleable(false, 'Si', 'No')
                ->sortable()
                ->searchable(),

            Column::make('Estado', 'state', 'people.state')
                ->toggleable(false, 'Activo', 'Inactivo')
                ->sortable()
                ->searchable(),

            Column::action('')
        ];
    }

    public function filters(): array
    {
        return [
            Filter::inputText('identification', 'people.identification')->operators(['contains']),
            Filter::inputText('name', 'people.name')->operators(['contains']),
            Filter::inputText('email', 'people.email')->operators(['contains']),
            Filter::inputText('phone', 'people.phone')->operators(['contains']),
            Filter::inputText('address', 'people.address')->operators(['contains']),
            Filter::inputText('whatsapp', 'people.whatsapp')->operators(['contains']),
            Filter::inputText('text', 'people.text')->operators(['contains']),
            Filter::inputText('isClient', 'people.isClient')->operators(['contains']),
            Filter::inputText('isSupplier', 'people.isSupplier')->operators(['contains']),
            Filter::inputText('isEmployee', 'people.isEmployee')->operators(['contains']),
            Filter::inputText('state', 'people.state')->operators(['contains']),
            Filter::datepicker('birth', 'people.birth'),
        ];
    }

    public function actionsFromView($row): View
    {
        $buttons = [
            [
                'name' => 'Editar',
                'route' => 'person.edit',
                'params' => ['person' => $row->id],
                'color' => 'blue',
                'icon' => 'fa fa-edit',
                'type' => 'button',
                'active' => true
            ],
            [
                'name' => 'Eliminar',
                'route' => 'person.destroy',
                'params' => ['person' => $row->id],
                'color' => 'red',
                'icon' => 'fa fa-trash-alt',
                'type' => 'delete',
                'active' => true
            ],
        ];

        return view('components.row-buttons', compact('buttons'));   
    }
    
}
