<?php

namespace App\Http\Controllers\master;

use App\Http\Controllers\Controller;
use App\Http\Requests\master\UnitRequest;
use App\Models\Master\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UnitController extends Controller
{
    public function index()
    {
        return view('master.unit.index');
    }

    public function create()
    {
        $units = Unit::where('state', 1)->get();
        return view('master.unit.create', compact('units'));
    }

    public function store(UnitRequest $request)
    {
        DB::beginTransaction();
        try {
            Unit::create([
                'name' => $request->name,
                'unit' => $request->unit,
                'time' => $request->time,
                'mass' => $request->mass,
                'longitude' => $request->longitude,
                'state' => $request->state ?? 1,
                'unit_id' => $request->unit_id,
                'factor' => $request->factor,
            ]);

            DB::commit();
            return redirect()->route('unit.index')->with('success', 'Se ha registrado la unidad correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Ha ocurrido un error, por favor reportar con el siguiente mensaje: '.$e->getMessage());
        }
    }

    public function edit(Unit $unit)
    {
        $units = Unit::where('state', 1)->where('id', '!=', $unit->id)->get();
        return view('master.unit.edit', compact('unit', 'units'));
    }

    public function update(UnitRequest $request, Unit $unit)
    {
        DB::beginTransaction();
        try {
            $unit->update([
                'name' => $request->name,
                'unit' => $request->unit,
                'time' => $request->time,
                'mass' => $request->mass,
                'longitude' => $request->longitude,
                'state' => $request->state ?? 1,
                'unit_id' => $request->unit_id,
                'factor' => $request->factor,
            ]);

            DB::commit();
            return redirect()->route('unit.index')->with('success', 'Se ha actualizado la unidad correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Ha ocurrido un error, por favor reportar con el siguiente mensaje: '.$e->getMessage());
        }
    }

    public function destroy(Unit $unit)
    {
        $unit->update(['state' => 0]);
        
        return redirect()->route('unit.index')->with('success', 'Se ha eliminado la unidad correctamente.');
    }
}
