<?php

namespace App\Http\Controllers\master;

use App\Http\Controllers\Controller;
use App\Http\Requests\Master\LocationRequest;
use App\Models\Master\Location;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LocationController extends Controller
{
    public function index()
    {
        return view('master.location.index');
    }

    public function create()
    {
        $locations = Location::where('company_id', Auth::user()->current_company_id)->get();
        return view('master.location.create', compact('locations'));
    }

    public function store(LocationRequest $request)
    {
        DB::beginTransaction();
        try {
            Location::create([
                'code' => $request->code,
                'name' => $request->name,
                'text' => $request->text,
                'ref_id' => $request->ref_id,
                'company_id' => Auth::user()->current_company_id,
                'state' => $request->state ?? 0,
            ]);

            DB::commit();
            return redirect()->route('location.index')->with('success', 'Se ha registrado la ubicación correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Ha ocurrido un error, por favor reportar con el siguiente mensaje: '.$e->getMessage());
        }
    }

    public function edit(Location $location)
    {
        $locations = Location::where('company_id', Auth::user()->current_company_id)
                                   ->where('id', '!=', $location->id)
                                   ->get();
        return view('master.location.edit', compact('location', 'locations'));
    }

    public function update(LocationRequest $request, Location $location)
    {
        DB::beginTransaction();
        try {
            $location->update([
                'code' => $request->code,
                'name' => $request->name,
                'text' => $request->text,
                'ref_id' => $request->ref_id,
                'company_id' => Auth::user()->current_company_id,
                'state' => $request->state ?? 0,
            ]);

            DB::commit();
            return redirect()->route('location.index')->with('success', 'Se ha actualizado la ubicación correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Ha ocurrido un error, por favor reportar con el siguiente mensaje: '.$e->getMessage());
        }
    }

    public function destroy(Location $location)
    {
        $location->update(['state' => 0]);
        
        return redirect()->route('location.index')->with('success', 'Se ha eliminado la ubicación correctamente.');
    }
}
