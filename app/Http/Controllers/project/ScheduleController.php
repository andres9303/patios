<?php

namespace App\Http\Controllers\project;

use App\Http\Controllers\Controller;
use App\Http\Requests\project\ScheduleRequest;
use App\Models\Master\Space;
use App\Models\Project\Activity;
use App\Models\Project\Project;
use App\Models\Project\Schedule;
use App\Services\EventService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ScheduleController extends Controller
{
    public function index()
    {
        return view('project.schedule.index');
    }

    public function create()
    {
        $spaces = Space::where('state', 1)->where('company_id', Auth::user()->current_company_id)->get();
        $projects = Project::where('state', 1)->where('type', 1)->where('company_id', Auth::user()->current_company_id)->get();
        
        return view('project.schedule.create', compact('spaces', 'projects'));
    }

    public function store(ScheduleRequest $request)
    {
        DB::beginTransaction();
        try {
            Schedule::create([
                'project_id' => $request->project_id,
                'space_id' => $request->space_id,
                'company_id' => Auth::user()->current_company_id,
                'date' => $request->date,
                'days' => $request->days,
                'cant' => $request->cant ?? 0,
                'saldo' => $request->cant ?? 0,
                'text' => $request->text,
                'state' => $request->state ?? 0,
            ]);

            DB::commit();
            return redirect()->route('schedule.index')->with('success', 'Se ha registrado la programación correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Ha ocurrido un error, por favor reportar con el siguiente mensaje: '.$e->getMessage());
        }
    }

    public function edit(Schedule $schedule)
    {
        $spaces = Space::where('state', 1)->where('company_id', Auth::user()->current_company_id)->get();
        $projects = Project::where('state', 1)->where('type', 1)->where('company_id', Auth::user()->current_company_id)->get();
        
        return view('project.schedule.edit', compact('schedule', 'spaces', 'projects'));
    }

    public function update(ScheduleRequest $request, Schedule $schedule)
    {
        DB::beginTransaction();
        try {
            $schedule->update([
                'project_id' => $request->project_id,
                'space_id' => $request->space_id,
                'date' => $request->date,
                'days' => $request->days,
                'cant' => $request->cant ?? 0,
                'saldo' => $request->cant == 0 ? 0 : $request->cant - Project::where('schedule_id', $schedule->id)->count('id') ?? 0,
                'text' => $request->text,
                'state' => $request->state ?? 0,
            ]);

            DB::commit();
            return redirect()->route('schedule.index')->with('success', 'Se ha actualizado la programación correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Ha ocurrido un error, por favor reportar con el siguiente mensaje: '.$e->getMessage());
        }
    }

    public function destroy(Schedule $schedule)
    {        
        DB::beginTransaction();
        try {
            $schedule->update([
                'state' => 0
            ]);

            DB::commit();
            return redirect()->route('schedule.index')->with('success', 'Se ha eliminado la programación correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Ha ocurrido un error, por favor reportar con el siguiente mensaje: '.$e->getMessage());
        }
    }

    public function schedule(Schedule $schedule, EventService $eventService)
    {
        DB::beginTransaction();
        try {
            if (Project::where('state', 1)->where('company_id', Auth::user()->current_company_id)->where('schedule_id', $schedule->id)->where('type', 1)->exists()) {
                return redirect()->route('schedule.index')->with('error', 'No se puede programar porque existen proyectos abiertos.');
            }

            if ($schedule->state == 0) {
                return redirect()->route('schedule.index')->with('error', 'No se puede programar porque la programación está inactiva.');
            }

            if ($schedule->saldo < 0) {
                return redirect()->route('schedule.index')->with('error', 'No se puede programar porque la cantidad es negativa.');
            }

            $project = Project::find($schedule->project_id);
            $newProject = Project::create([
                'name' => $project->name . ' - ' . Carbon::now()->format('mY'),
                'text' => $project->text,
                'state' => 1,
                'company_id' => $project->company_id,
                'schedule_id' => $schedule->id,
                'type' => $project->type,
                'space_id' => $schedule->space_id,
                'item_id' => $project->item_id,
                'concept' => $project->concept,
            ]);

            if ($newProject->space_id) {
                $eventService->create('EVNT_InPj', $newProject->space_id, Carbon::now(), 'Proyecto abierto: '. $newProject->name);
            }

            foreach ($project->activities as $activity) {
                Activity::create([
                    'code' => $activity->code,
                    'name' => $activity->name,
                    'unit_id' => $activity->unit_id,
                    'text' => $activity->text,
                    'state' => $activity->state,
                    'cant' => $activity->cant,
                    'value' => $activity->value,
                    'cost' => $activity->cost,
                    'start_date' => $activity->start_date,
                    'end_date' => $activity->end_date,
                    'type' => $activity->type,
                    'activity_id' => $activity->id,
                    'project_id' => $newProject->id,
                ]);
            }

            $schedule->update([
                'date' => Carbon::now(),
                'saldo' => $schedule->cant - Project::where('schedule_id', $schedule->id)->count('id'),
            ]);

            DB::commit();
            return redirect()->route('project.index')->with('success', 'Se ha registrado el proyecto correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Ha ocurrido un error, por favor reportar con el siguiente mensaje: '.$e->getMessage());
        }
    }
}
