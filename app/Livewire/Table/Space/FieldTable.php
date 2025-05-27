<?php

namespace App\Livewire\Table\Space;

use App\Models\Space\Field;
use App\Models\Space\Template;
use App\Models\Space\TypeField;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\View\View;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Components\SetUp\Exportable;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\Rule;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;

final class FieldTable extends PowerGridComponent
{
    public Template $template;
    public string $tableName = 'lpg-space-field-table';

    public function setUp(): array
    {
        $this->showCheckBox();

        return [
            PowerGrid::exportable('plantilla_'.$this->template->name.'_campos_'.Carbon::now()->format('Y-m-d'))
                ->striped()
                ->type(Exportable::TYPE_XLS, Exportable::TYPE_CSV),
            PowerGrid::header()
                ->showSearchInput(),
            PowerGrid::footer()
                ->showPerPage()
                ->showRecordCount(),
        ];
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function datasource(): Builder
    {
        return Field::query()
            ->join('type_fields', 'type_fields.id', '=', 'fields.type_field_id')
            ->where('template_id', $this->template->id)
            ->select([
                'fields.*',
                'type_fields.name as type_field_name',
            ])
            ->orderBy('name', 'asc');
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('name')
            ->add('description')
            ->add('type_field_name')
            ->add('is_description')
            ->add('is_required')
            ->add('state')
            ->add('order');
    }

    public function columns(): array
    {
        return [
            Column::make('Nombre Campo', 'name', 'fields.name')
                ->sortable()
                ->searchable(),

            Column::make('Tipo Campo', 'type_field_name', 'type_fields.id')
                ->sortable()
                ->searchable(),

            Column::make('Descripción', 'description', 'fields.description')
                ->sortable()
                ->searchable(),

            Column::make('¿Mostrar Descripción?', 'is_description', 'fields.is_description')
                ->toggleable(false, 'Si', 'No')
                ->sortable()
                ->searchable(),

            Column::make('¿Campo Requerido?', 'is_required', 'fields.is_required')
                ->toggleable(false, 'Si', 'No')
                ->sortable()
                ->searchable(),

            Column::make('Estado', 'state', 'fields.state')
                ->toggleable(false, 'Activo', 'Inactivo')
                ->sortable()
                ->searchable(),

            Column::make('Orden', 'order', 'fields.order')
                ->sortable()
                ->searchable(),

            Column::action('')
        ];
    }

    public function filters(): array
    {
        return [
            Filter::inputText('name', 'fields.name')->operators(['contains']),
            Filter::select('type_field_name', 'type_fields.id')->dataSource(TypeField::where('state', 1)->orderBy('name')->get())->optionLabel('name')->optionValue('id'),
            Filter::inputText('description', 'fields.description')->operators(['contains']),
            Filter::select('is_description', 'fields.is_description')->dataSource([['id' => 1, 'name' => 'Si'], ['id' => 0, 'name' => 'No']])->optionLabel('name')->optionValue('id'),
            Filter::select('is_required', 'fields.is_required')->dataSource([['id' => 1, 'name' => 'Si'], ['id' => 0, 'name' => 'No']])->optionLabel('name')->optionValue('id'),
            Filter::select('state', 'fields.state')->dataSource([['id' => 1, 'name' => 'Activo'], ['id' => 0, 'name' => 'Inactivo']])->optionLabel('name')->optionValue('id'),
            Filter::inputText('order', 'fields.order')->operators(['contains']),
        ];
    }

    public function actionsFromView($row): View
    {
        $buttons = [
            [
                'name' => 'Editar',
                'route' => 'field.edit',
                'params' => ['template' => $this->template->id, 'field' => $row->id],
                'color' => 'blue',
                'icon' => 'fa fa-edit',
                'type' => 'button',
                'active' => true
            ],
            [
                'name' => 'Eliminar',
                'route' => 'field.destroy',
                'params' => ['template' => $this->template->id, 'field' => $row->id],
                'color' => 'red',
                'icon' => 'fa fa-trash',
                'type' => 'button',
                'active' => true
            ]
        ];

        return view('components.row-buttons', compact('buttons'));
    }
}
