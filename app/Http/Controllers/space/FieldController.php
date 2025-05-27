<?php

namespace App\Http\Controllers\space;

use App\Http\Controllers\Controller;
use App\Http\Requests\Space\FieldRequest;
use App\Models\Space\Field;
use App\Models\Space\Template;
use App\Models\Space\TypeField;
use Illuminate\Support\Facades\DB;

class FieldController extends Controller
{
    public function index(Template $template)
    {
        return view('space.template.field.index', compact('template'));
    }

    public function create(Template $template)
    {
        $typeFields = TypeField::where('state', 1)->get();

        return view('space.template.field.create', compact('template', 'typeFields'));
    }

    public function store(FieldRequest $request, Template $template)
    {
        DB::beginTransaction();
        try {
            Field::create([
                'name' => $request->name,
                'description' => $request->description,
                'template_id' => $template->id,
                'type_field_id' => $request->type_field_id,
                'is_description' => $request->is_description ?? 0,
                'is_required' => $request->is_required ?? 0,
                'state' => $request->state,
            ]);
            DB::commit();
            return redirect()->route('field.index', ['template' => $template->id])->with('success', 'Se ha registrado el campo correctamente.');
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Ha ocurrido un error, por favor reportar con el siguiente mensaje: '.$th->getMessage());
        }
    }

    public function edit(Template $template, Field $field)
    {
        $typeFields = TypeField::where('state', 1)->get();

        return view('space.template.field.edit', compact('template', 'field', 'typeFields'));
    }

    public function update(FieldRequest $request, Template $template, Field $field)
    {
        DB::beginTransaction();
        try {
            $field->update([
                'name' => $request->name,
                'description' => $request->description,
                'type_field_id' => $request->type_field_id,
                'is_description' => $request->is_description ?? 0,
                'is_required' => $request->is_required ?? 0,
                'state' => $request->state,
            ]);
            DB::commit();
            return redirect()->route('field.index', ['template' => $template->id])->with('success', 'Se ha actualizado el campo correctamente.');
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Ha ocurrido un error, por favor reportar con el siguiente mensaje: '.$th->getMessage());
        }
    }

    public function destroy(Template $template, Field $field)
    {
        DB::beginTransaction();
        try {
            $field->update([
                'state' => 0,
            ]);
            DB::commit();
            return redirect()->route('field.index', ['template' => $template->id])->with('success', 'Se ha eliminado el campo correctamente.');
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Ha ocurrido un error, por favor reportar con el siguiente mensaje: '.$th->getMessage());
        }
    }
}
