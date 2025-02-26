<?php

namespace App\Http\Controllers\project;

use App\Http\Controllers\Controller;
use App\Http\Requests\project\ActivityRequest;
use App\Models\Master\Unit;
use App\Models\Project\Activity;
use App\Models\Project\Project;
use Illuminate\Support\Facades\DB;

class ActivityController extends Controller
{
    public function index(Project $project)
    {
        return view('project.activity.index', compact('project'));
    }

    public function create(Project $project)
    {
        $units = Unit::where('state', 1)->get();
        return view('project.activity.create', compact('project', 'units'));
    }

    public function store(ActivityRequest $request, Project $project)
    {
        DB::beginTransaction();
        try {
            $existingActivity = Activity::where('project_id', $project->id)
                ->where('code', $request->code)
                ->first();

            if ($existingActivity) {
                return back()->withInput()->with('error', 'Ya existe una actividad con el mismo código en este proyecto.');
            }

            Activity::create([
                'project_id' => $project->id,
                'code' => $request->code,
                'name' => $request->name,
                'unit_id' => $request->unit_id,
                'text' => $request->text,
                'state' => $request->state ?? 1,
                'cant' => $request->cant,
                'value' => $request->value,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
            ]);

            DB::commit();
            return redirect()->route('activity.index', $project)->with('success', 'Se ha registrado la actividad correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Ha ocurrido un error, por favor reportar con el siguiente mensaje: '.$e->getMessage());
        }
    }

    public function edit(Project $project, Activity $activity)
    {
        $units = Unit::where('state', 1)->get();
        return view('project.activity.edit', compact('project', 'activity', 'units'));
    }

    public function update(ActivityRequest $request, Project $project, Activity $activity)
    {
        DB::beginTransaction();
        try {
            $existingActivity = Activity::where('project_id', $project->id)
                ->where('code', $request->code)
                ->where('id', '!=', $activity->id)
                ->first();

            if ($existingActivity) {
                return back()->withInput()->with('error', 'Ya existe una actividad con el mismo código en este proyecto.');
            }

            $activity->update([
                'code' => $request->code,
                'name' => $request->name,
                'unit_id' => $request->unit_id,
                'text' => $request->text,
                'state' => $request->state ?? 1,
                'cant' => $request->cant,
                'value' => $request->value,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
            ]);

            DB::commit();
            return redirect()->route('activity.index', $project)->with('success', 'Se ha actualizado la actividad correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Ha ocurrido un error, por favor reportar con el siguiente mensaje: '.$e->getMessage());
        }
    }

    public function destroy(Project $project, Activity $activity)
    {
        $activity->update(['state' => 0]);
        
        return redirect()->route('activity.index', $project)->with('success', 'Se ha eliminado la actividad correctamente.');
    }
}
