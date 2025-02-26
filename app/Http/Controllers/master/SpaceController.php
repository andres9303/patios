<?php

namespace App\Http\Controllers\master;

use App\Http\Controllers\Controller;
use App\Http\Requests\master\SpaceRequest;
use App\Models\Config\Item;
use App\Models\Master\Space;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SpaceController extends Controller
{
    public function index()
    {
        return view('master.space.index');
    }

    public function create()
    {
        $categories = Item::where('catalog_id', 20801)->get();
        $classes = Item::where('catalog_id', 20801)->get();
        $spaces = Space::where('state', 1)->get();

        return view('master.space.create', compact('categories', 'classes', 'spaces'));
    }

    public function store(SpaceRequest $request)
    {
        DB::beginTransaction();
        try {
            Space::create([
                'name' => $request->name,
                'text' => $request->text,
                'order' => $request->order,
                'state' => $request->state ?? 1,
                'item_id' => $request->item_id, // Categoría
                'item2_id' => $request->item2_id, // Clase
                'cant' => $request->cant, // Capacidad instalada
                'space_id' => $request->space_id,
            ]);

            DB::commit();
            return redirect()->route('space.index')->with('success', 'Se ha registrado el espacio correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Ha ocurrido un error, por favor reportar con el siguiente mensaje: '.$e->getMessage());
        }
    }

    public function edit(Space $space)
    {
        $categories = Item::where('catalog_id', 20801)->get(); // Categorías (item_id)
        $classes = Item::where('catalog_id', 20801)->get(); // Clases (item2_id)
        $spaces = Space::where('state', 1)->where('id', '!=', $space->id)->get(); // Espacios padre
        return view('master.space.edit', compact('space', 'categories', 'classes', 'spaces'));
    }

    public function update(SpaceRequest $request, Space $space)
    {
        DB::beginTransaction();
        try {
            $space->update([
                'name' => $request->name,
                'text' => $request->text,
                'order' => $request->order,
                'state' => $request->state ?? 1,
                'item_id' => $request->item_id, // Categoría
                'item2_id' => $request->item2_id, // Clase
                'cant' => $request->cant, // Capacidad instalada
                'space_id' => $request->space_id, // Espacio padre
            ]);

            DB::commit();
            return redirect()->route('space.index')->with('success', 'Se ha actualizado el espacio correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Ha ocurrido un error, por favor reportar con el siguiente mensaje: '.$e->getMessage());
        }
    }

    public function destroy(Space $space)
    {
        $space->update(['state' => 0]);
        
        return redirect()->route('space.index')->with('success', 'Se ha eliminado el espacio correctamente.');
    }
}
