<?php

namespace App\Http\Controllers\Event;

use App\Http\Controllers\Controller;
use App\Http\Requests\Event\TimetableRequest;
use App\Models\Config\Item;
use App\Models\Event\TimeTable;
use App\Models\Master\Person;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TimetableController extends Controller
{
    public function index()
    {
        return view('event.timetable.index');
    }

    public function create()
    {
        $activities = Item::where('catalog_id', 70002)->orderBy('order', 'asc')->get();
        $persons = Person::where('state', 1)->where('isOperator', 1)->orderBy('name', 'asc')->get();
        return view('event.timetable.create', compact('activities', 'persons'));
    }

    public function store(TimetableRequest $request)
    {
        DB::beginTransaction();
        try {
            TimeTable::create([
                'date' => $request->date,
                'company_id' => Auth::user()->current_company_id,
                'item_id' => $request->item_id,
                'person_id' => $request->person_id,
                'user_id' => Auth::user()->id,
                'text' => $request->text,
                'percentage' => $request->percentage,
                'cant' => $request->cant,
            ]);

            DB::commit();
            return redirect()->route('timetable.index')->with('success', 'Se ha registrado la programación de la agenda correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Ha ocurrido un error, por favor reportar con el siguiente mensaje: '.$e->getMessage());
        }
    }

    public function edit(TimeTable $timetable)
    {
        $activities = Item::where('catalog_id', 70002)->orderBy('order', 'asc')->get();
        $persons = Person::where('state', 1)->where('isOperator', 1)->orderBy('name', 'asc')->get();
        return view('event.timetable.edit', compact('timetable', 'activities', 'persons'));
    }

    public function update(TimetableRequest $request, TimeTable $timetable)
    {
        DB::beginTransaction();
        try {
            $timetable->update([
                'date' => $request->date,
                'company_id' => Auth::user()->current_company_id,
                'item_id' => $request->item_id,
                'person_id' => $request->person_id,
                'user_id' => Auth::user()->id,
                'text' => $request->text,
                'percentage' => $request->percentage,
                'cant' => $request->cant,
            ]);

            DB::commit();
            return redirect()->route('timetable.index')->with('success', 'Se ha actualizado la programación de la agenda correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Ha ocurrido un error, por favor reportar con el siguiente mensaje: '.$e->getMessage());
        }
    }

    public function destroy(TimeTable $timetable)
    {
        DB::beginTransaction();
        try {
            $timetable->delete();
            DB::commit();
            return redirect()->route('timetable.index')->with('success', 'Se ha eliminado la programación de la agenda correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Ha ocurrido un error, por favor reportar con el siguiente mensaje: '.$e->getMessage());
        }
    }
}
