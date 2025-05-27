<?php

namespace App\Http\Controllers\master;

use App\Http\Controllers\Controller;
use App\Http\Requests\Master\EventTypeRequest;
use App\Models\Config\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EventTypeController extends Controller
{
    private int $catalog_id = 20803;

    public function index()
    {
        return view('master.event-type.index');
    }

    public function create()
    {
        return view('master.event-type.create');
    }

    public function store(EventTypeRequest $request)
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
            return redirect()->route('event-type.index')->with('success', 'Se ha registrado el tipo de evento correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Ha ocurrido un error, por favor reportar con el siguiente mensaje: '.$e->getMessage());
        }
    }

    public function edit(Item $event_type)
    {
        return view('master.event-type.edit', compact('event_type'));
    }

    public function update(EventTypeRequest $request, Item $event_type)
    {
        DB::beginTransaction();
        try {
            $event_type->update([
                'name' => $request->name,
                'text' => $request->text,
                'order' => $request->order,
            ]);

            DB::commit();
            return redirect()->route('event-type.index')->with('success', 'Se ha actualizado el tipo de evento correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Ha ocurrido un error, por favor reportar con el siguiente mensaje: '.$e->getMessage());
        }
    }

    public function destroy(Item $event_type)
    {
        DB::beginTransaction();
        try {
            $event_type->delete();
            DB::commit();
            return redirect()->route('event-type.index')->with('success', 'Se ha eliminado el tipo de evento correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Ha ocurrido un error, por favor reportar con el siguiente mensaje: '.$e->getMessage());
        }
    }
}
