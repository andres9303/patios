<?php

namespace App\Http\Controllers\project;

use App\Http\Controllers\Controller;
use App\Http\Requests\project\ProjectRequest;
use App\Models\Config\Item;
use App\Models\Master\Space;
use App\Models\Project\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProjectController extends Controller
{
    public function index()
    {
        return view('project.project.index');
    }

    public function create()
    {
        $classifications = Item::where('catalog_id', 40101)->get(); // Clasificaciones (item_id)
        $spaces = Space::where('state', 1)->get(); // Espacios
        return view('project.project.create', compact('classifications', 'spaces'));
    }

    public function store(ProjectRequest $request)
    {
        DB::beginTransaction();
        try {
            Project::create([
                'company_id' => Auth::user()->current_company_id,
                'name' => $request->name,
                'text' => $request->text,
                'state' => $request->state ?? 1,
                'concept' => $request->concept,
                'type' => $request->type,
                'item_id' => $request->item_id, // Clasificación
                'space_id' => $request->space_id, // Espacio
            ]);

            DB::commit();
            return redirect()->route('project.index')->with('success', 'Se ha registrado el proyecto correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Ha ocurrido un error, por favor reportar con el siguiente mensaje: '.$e->getMessage());
        }
    }

    public function edit(Project $project)
    {
        $classifications = Item::where('catalog_id', 40101)->get();
        $spaces = Space::where('state', 1)->get(); // Espacios
        $types = [
            ['id' => 1, 'name' => 'Presupuesto'],
            ['id' => 2, 'name' => 'Proyecto'],
        ]; // Tipos de proyecto
        return view('project.project.edit', compact('project', 'classifications', 'spaces', 'types'));
    }

    public function update(ProjectRequest $request, Project $project)
    {
        DB::beginTransaction();
        try {
            $project->update([
                'name' => $request->name,
                'text' => $request->text,
                'state' => $request->state ?? 1,
                'concept' => $request->concept,
                'type' => $request->type,
                'item_id' => $request->item_id, // Clasificación
                'space_id' => $request->space_id, // Espacio
            ]);

            DB::commit();
            return redirect()->route('project.index')->with('success', 'Se ha actualizado el proyecto correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Ha ocurrido un error, por favor reportar con el siguiente mensaje: '.$e->getMessage());
        }
    }

    public function destroy(Project $project)
    {
        $project->update(['state' => 0]);
        
        return redirect()->route('project.index')->with('success', 'Se ha eliminado el proyecto correctamente.');
    }
}
