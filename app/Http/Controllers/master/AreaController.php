<?php

namespace App\Http\Controllers\master;

use App\Http\Controllers\Controller;
use App\Http\Requests\Master\AreaRequest;
use App\Models\Config\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AreaController extends Controller
{
    private int $catalog_id = 20701;

    public function index()
    {
        return view('master.area.index');
    }

    public function create()
    {
        return view('master.area.create');
    }

    public function store(AreaRequest $request)
    {
        DB::beginTransaction();
        try {
            Item::create([
                'name' => $request->name,
                'text' => $request->text,
                'order' => $request->order,
                'catalog_id' => $this->catalog_id,
            ]);

            DB::commit();
            return redirect()->route('area.index')->with('success', 'Se ha registrado el área correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Ha ocurrido un error, por favor reportar con el siguiente mensaje: '.$e->getMessage());
        }
    }

    public function edit(Item $area)
    {
        return view('master.area.edit', compact('area'));
    }

    public function update(AreaRequest $request, Item $area)
    {
        DB::beginTransaction();
        try {
            $area->update([
                'name' => $request->name,
                'text' => $request->text,
                'order' => $request->order,
            ]);

            DB::commit();
            return redirect()->route('area.index')->with('success', 'Se ha actualizado el área correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Ha ocurrido un error, por favor reportar con el siguiente mensaje: '.$e->getMessage());
        }
    }

    public function destroy(Item $area)
    {
        DB::beginTransaction();
        try {
            $area->delete();
            DB::commit();
            return redirect()->route('area.index')->with('success', 'Se ha eliminado el área correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Ha ocurrido un error, por favor reportar con el siguiente mensaje: '.$e->getMessage());
        }
    }
}
