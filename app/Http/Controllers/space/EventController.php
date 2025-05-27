<?php

namespace App\Http\Controllers\space;

use App\Http\Controllers\Controller;
use App\Http\Requests\Space\EventRequest;
use App\Models\Config\Item;
use App\Models\Config\Variable;
use App\Models\Master\Space;
use App\Models\Space\Event;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EventController extends Controller
{
    private int $eventType = 20803;

    public function index()
    {
        return view('space.event.index');
    }

    public function create()    
    {
        $eventVariable = Variable::where('cod', 'EVNT')->first();
        if (!$eventVariable) {
            return redirect()->route('event.index')->with('error', 'No se encontró la variable de eventos.');
        }

        $excludedConcepts = Variable::where('variable_id', $eventVariable->id)->pluck('concept')->toArray();
        $eventTypes = Item::where('catalog_id', $this->eventType)->whereNotIn('id', $excludedConcepts)->get();
        $spaces = Space::where('state', 1)->where('company_id', Auth::user()->current_company_id)->get();

        return view('space.event.create', compact('eventTypes', 'spaces'));
    }

    public function store(EventRequest $request)
    {        
        DB::beginTransaction();
        try {
            Event::create([
                'title' => $request->title,
                'text' => $request->text,
                'company_id' => Auth::user()->current_company_id,
                'item_id' => $request->item_id,
                'space_id' => $request->space_id,
                'date' => $request->date,
                'time' => $request->time,
                'location' => $request->location,
                'state' => 1,
                'user_id' => Auth::id(),
            ]);
            
            DB::commit();
            return redirect()->route('event.index')->with('success', 'Se ha registrado el evento correctamente.');
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Ha ocurrido un error, por favor reportar con el siguiente mensaje: '.$th->getMessage());
        }        
    }

    public function edit(Event $event)
    {
        $eventVariable = Variable::where('cod', 'EVNT')->first();
        if (!$eventVariable) {
            return redirect()->route('event.index')->with('error', 'No se encontró la variable de eventos.');
        }

        $excludedConcepts = Variable::where('variable_id', $eventVariable->id)->pluck('concept')->toArray();
        $eventTypes = Item::where('catalog_id', $this->eventType)->whereNotIn('id', $excludedConcepts)->get();
        $spaces = Space::where('state', 1)->where('company_id', Auth::user()->current_company_id)->get();

        return view('space.event.edit', compact('event', 'eventTypes', 'spaces'));
    }

    public function update(EventRequest $request, Event $event)
    {
        DB::beginTransaction();
        try {
            $event->update([
                'title' => $request->title,
                'text' => $request->text,
                'item_id' => $request->item_id,
                'space_id' => $request->space_id,
                'date' => $request->date,
                'time' => $request->time,
                'location' => $request->location,
                'state' => 1,
            ]);
            
            DB::commit();
            return redirect()->route('event.index')->with('success', 'Se ha actualizado el evento correctamente.');
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Ha ocurrido un error, por favor reportar con el siguiente mensaje: '.$th->getMessage());
        }
    }

    public function destroy(Event $event)
    {
        DB::beginTransaction();
        try {
            $event->update([
                'state' => 0,
            ]);
            DB::commit();
            return redirect()->route('event.index')->with('success', 'Se ha eliminado el evento correctamente.');
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Ha ocurrido un error, por favor reportar con el siguiente mensaje: '.$th->getMessage());
        }
    }
}
