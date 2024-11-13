<?php

namespace App\Http\Controllers\Ticket;

use App\Http\Controllers\Controller;
use App\Http\Requests\Ticket\TicketRequest;
use App\Models\Master\Category;
use App\Models\Master\Location;
use App\Models\Master\Person;
use App\Models\Ticket\Ticket;
use App\Models\Ticket\Tracking;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TicketController extends Controller
{
    public function index()
    {
        return view('ticket.ticket.index');
    }

    public function create()
    {
        $persons = Person::where('state', 1)->where('isClient', 1)->get();
        $locations = Location::where('state', 1)->where(function ($query) {
            $query->where('company_id', Auth::user()->current_company_id)
                  ->orWhere('company_id', 1);
        })->orderBy('name')->get();
        $categories = Category::where('state', 1)->where(function ($query) {
            $query->where('company_id', Auth::user()->current_company_id)
                  ->orWhere('company_id', 1);
        })->orderBy('name')->get();
        return view('ticket.ticket.create', compact('persons', 'locations', 'categories'));
    }

    public function store(TicketRequest $request)
    {
        DB::beginTransaction();
        try {
            Ticket::create([
                'date' => $request->date,
                'company_id' => Auth::user()->current_company_id,
                'person_id' => $request->person_id,
                'location_id' => $request->location_id,
                'category_id' => $request->category_id,
                'category2_id' => $request->category2_id,
                'text' => $request->text,
                'state' => 0,
                'user_id' => Auth::user()->id,
            ]);

            DB::commit();
            return redirect()->route('ticket.index')->with('success', 'El ticket ha sido creado correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Ha ocurrido un error, por favor reportar con el siguiente mensaje: '.$e->getMessage());
        }
    }

    public function show(Ticket $ticket)
    {
        return view('ticket.ticket.show', compact('ticket'));
    }

    public function edit(Ticket $ticket)
    {
        $persons = Person::where('state', 1)->where('isClient', 1)->get();
        $locations = Location::where('state', 1)->where(function ($query) {
            $query->where('company_id', Auth::user()->current_company_id)
                  ->orWhere('company_id', 1);
        })->get();
        $categories = Category::where('state', 1)->where(function ($query) {
            $query->where('company_id', Auth::user()->current_company_id)
                  ->orWhere('company_id', 1);
        })->get();
        return view('ticket.ticket.edit', compact('ticket', 'persons', 'locations', 'categories'));
    }

    public function update(TicketRequest $request, Ticket $ticket)
    {
        DB::beginTransaction();
        try {
            $ticket->update([
                'date' => $request->date,
                'company_id' => Auth::user()->current_company_id,
                'person_id' => $request->person_id,
                'location_id' => $request->location_id,
                'category_id' => $request->category_id,
                'category2_id' => $request->category2_id,
                'text' => $request->text,
                'state' => 0,
                'user_id' => Auth::user()->id,
            ]);

            DB::commit();
            return redirect()->route('ticket.index')->with('success', 'El ticket ha sido actualizado correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Ha ocurrido un error, por favor reportar con el siguiente mensaje: '.$e->getMessage());
        }
    }

    public function destroy(Ticket $ticket)
    {
        DB::beginTransaction();
        try {
            $ticket->update(['state' => 1]);
            
            Tracking::create([
                'ticket_id' => $ticket->id,
                'date' => Carbon::now(),
                'state' => 1,
                'user_id' => Auth::user()->id,
                'text' => 'Ticket eliminado por '.Auth::user()->name,
                'type' => 3,
            ]);

            DB::commit();
        
            return redirect()->route('ticket.index')->with('success', 'El ticket ha sido eliminado correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Ha ocurrido un error, por favor reportar con el siguiente mensaje: '.$e->getMessage());
        }
    }
}