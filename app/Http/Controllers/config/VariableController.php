<?php

namespace App\Http\Controllers\config;

use App\Http\Controllers\Controller;
use App\Http\Requests\Config\VariableRequest;
use App\Models\Config\Variable;
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

    public function store(VariableRequest $request)
    {
        DB::transaction(function () use ($request) {
            Variable::create($request->validated());
        });

        return redirect()->route('variable.index')->with('success', 'Se ha registrado la variable correctamente.');
    }

    public function edit(Variable $variable)
    {
        $variables = Variable::all()->prepend(['id' => null, 'name' => '-']);

        return view('config.variable.edit', compact('variable', 'variables'));
    }

    public function update(VariableRequest $request, Variable $variable)
    {
        DB::transaction(function () use ($request, $variable) {
            $variable->fill($request->validated())->save();
        });

        return redirect()->route('variable.index')->with('success', 'Se ha actualizado la variable correctamente.');
    }

    public function destroy(Variable $variable)
    {
        DB::transaction(function () use ($variable) {
            $variable->delete();
        });

        return redirect()->route('variable.index')->with('success', 'Se ha eliminado la variable correctamente.');
    }
}
