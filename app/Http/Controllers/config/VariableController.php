<?php

namespace App\Http\Controllers\config;

use App\Http\Controllers\Controller;
use App\Models\Config\Variable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VariableController extends Controller
{
    public function index()
    {
        return view('config.variable.index');
    }

    public function create()
    {
        $variables = Variable::all()->prepend(['id' => null, 'name' => '-']);

        return view('config.variable.create', compact('variables'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' =>'required|string|max:255',
            'cod' =>'required',
        ]);

        DB::beginTransaction();
        try {            
            Variable::create([
                'cod' => $request->cod,
                'name' => $request->name,
                'text' => $request->text,
                'concept' => $request->concept,
                'value' => $request->value,
                'variable_id' => $request->variable_id,
            ]);

            DB::commit();
            return redirect()->route('variable.index')->with('success', 'Se ha registrado la variable correctamente.');
        }
        catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Ha ocurrido un error, por favor reportar con el siguiente mensaje: '.$e->getMessage());
        }
    }

    public function edit(Variable $variable)
    {
        $variables = Variable::all()->prepend(['id' => null, 'name' => '-']);

        return view('config.variable.edit', compact('variable', 'variables'));
    }

    public function update(Request $request, Variable $variable)
    {
        $request->validate([
            'name' =>'required|string|max:255',
            'cod' =>'required',
        ]);

        DB::beginTransaction();
        try {
            $variable->update([
                'cod' => $request->cod,
                'name' => $request->name,
                'text' => $request->text,
                'concept' => $request->concept,
                'value' => $request->value,
                'variable_id' => $request->variable_id,
            ]);

            DB::commit();
            return redirect()->route('variable.index')->with('success', 'Se ha actualizado la variable correctamente.');
        }
        catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Ha ocurrido un error, por favor reportar con el siguiente mensaje: '.$e->getMessage());
        }
    }

    public function destroy(Variable $variable)
    {
        $variable->delete();

        return redirect()->route('variable.index')->with('success', 'Se ha eliminado la variable correctamente.');
    }
}
