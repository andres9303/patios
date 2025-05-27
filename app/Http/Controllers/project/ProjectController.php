<?php

namespace App\Http\Controllers\project;

use App\Http\Controllers\Controller;
use App\Http\Requests\project\ProjectRequest;
use App\Models\Config\Item;
use App\Models\Master\Space;
use App\Models\Project\Project;
use App\Services\EventService;
use Carbon\Carbon;
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

    public function store(ProjectRequest $request, EventService $eventService)
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

            if ($request->space_id) {
                $eventService->create('EVNT_InPj', $request->space_id, Carbon::now(), 'Nuevo Proyecto: '. $request->name);
            }

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

    public function update(ProjectRequest $request, Project $project, EventService $eventService)
    {
        DB::beginTransaction();
        try {
            $project->update([
                'name' => $request->name,
                'text' => $request->text,
                'state' => ($project->state != 2 ? $request->state ?? 0 : 2),
                'concept' => $request->concept,
                'type' => $request->type,
                'item_id' => $request->item_id, // Clasificación
                'space_id' => $request->space_id, // Espacio
            ]);

            if ($request->space_id) {
                if ($project->space_id != $request->space_id) {
                    $eventService->create('EVNT_InPj', $project->space_id, Carbon::now(), 'Cambio de espacio en proyecto: '. $request->name);
                    $eventService->create('EVNT_InPj', $request->space_id, Carbon::now(), 'Nuevo Proyecto: '. $request->name);
                }
                else {
                    $eventService->create('EVNT_InPj', $request->space_id, Carbon::now(), 'Actualización Proyecto: '. $request->name);
                }
            }

            DB::commit();
            return redirect()->route('project.index')->with('success', 'Se ha actualizado el proyecto correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Ha ocurrido un error, por favor reportar con el siguiente mensaje: '.$e->getMessage());
        }
    }   

    public function complete(Project $project, EventService $eventService)
    {
        DB::beginTransaction();
        try {
            if ($project->state == 1) {
                if ($project->space_id) {
                    $eventService->create('EVNT_FiPj', $project->space_id, Carbon::now(), 'Proyecto finalizado: '. $project->name);
                }
                $project->update(['state' => 2]);
                DB::commit();
                return redirect()->route('project.index')->with('success', 'Se ha finalizado el proyecto correctamente.');
            }

            return redirect()->route('project.index')->with('error', 'No se puede finalizar un proyecto finalizado.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Ha ocurrido un error, por favor reportar con el siguiente mensaje: '.$e->getMessage());
        }
    }

    public function open(Project $project, EventService $eventService)
    {
        DB::beginTransaction();
        try {
            if ($project->state == 2) {
                if ($project->space_id) {
                    $eventService->create('EVNT_InPj', $project->space_id, now(), 'Proyecto reabierto: '. $project->name);
                }
                $project->update(['state' => 1]);
                DB::commit();
                return redirect()->route('project.index')->with('success', 'Se ha reabierto el proyecto correctamente.');
            }

            return redirect()->route('project.index')->with('error', 'No se puede reabrir un proyecto abierto.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Ha ocurrido un error, por favor reportar con el siguiente mensaje: '.$e->getMessage());
        }
    }

    public function destroy(Project $project, EventService $eventService)
    {
        DB::beginTransaction();
        try {
            if ($project->state == 2) {
                if ($project->space_id) {
                    $eventService->create('EVNT_AnPj', $project->space_id, Carbon::now(), 'Proyecto anulado: '. $project->name);
                }
                return redirect()->route('project.index')->with('error', 'No se puede eliminar un proyecto finalizado.');
            }

            $project->update(['state' => 0]);
            DB::commit();
            return redirect()->route('project.index')->with('success', 'Se ha eliminado el proyecto correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Ha ocurrido un error, por favor reportar con el siguiente mensaje: '.$e->getMessage());
        }
    }
}
