<?php

namespace App\Http\Controllers\Event;

use App\Http\Controllers\Controller;
use App\Http\Requests\Event\MeetingRequest;
use App\Models\Config\Item;
use Illuminate\Support\Facades\DB;

class MeetingController extends Controller
{
    private int $catalog_id = 70002;

    public function index()
    {
        return view('event.meeting.index');
    }

    public function create()
    {
        $categories = Item::where('catalog_id', 70001)->orderBy('order', 'asc')->get();
        return view('event.meeting.create', compact('categories'));
    }

    public function store(MeetingRequest $request)
    {
        DB::beginTransaction();
        try {
            Item::create([
                'name' => $request->name,
                'text' => $request->text,
                'order' => $request->order,
                'catalog_id' => $this->catalog_id,
                'item_id' => $request->item_id,
            ]);

            DB::commit();
            return redirect()->route('meeting.index')->with('success', 'Se ha registrado la actividad correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Ha ocurrido un error, por favor reportar con el siguiente mensaje: '.$e->getMessage());
        }
    }

    public function edit(Item $meeting)
    {
        $categories = Item::where('catalog_id', 70001)->orderBy('order', 'asc')->get();
        return view('event.meeting.edit', compact('meeting', 'categories'));
    }

    public function update(MeetingRequest $request, Item $meeting)
    {
        DB::beginTransaction();
        try {
            $meeting->update([
                'name' => $request->name,
                'text' => $request->text,
                'order' => $request->order,
                'item_id' => $request->item_id,
            ]);

            DB::commit();
            return redirect()->route('meeting.index')->with('success', 'Se ha actualizado la actividad correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Ha ocurrido un error, por favor reportar con el siguiente mensaje: '.$e->getMessage());
        }
    }

    public function destroy(Item $meeting)
    {
        DB::beginTransaction();
        try {
            $meeting->delete();
            DB::commit();
            return redirect()->route('meeting.index')->with('success', 'Se ha eliminado la actividad correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Ha ocurrido un error, por favor reportar con el siguiente mensaje: '.$e->getMessage());
        }
    }
}
