<?php

namespace App\Http\Controllers\Ticket;

use App\Http\Controllers\Controller;
use App\Http\Requests\Ticket\ManageTicketRequest;
use App\Models\Config\Variable;
use App\Models\Master\Category;
use App\Models\Ticket\Ticket;
use App\Models\Ticket\Tracking;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ManageTicketController extends Controller
{
    public function index()
    {
        return view('ticket.manage.index');
    }

    public function create()
    {
        return view('ticket.manage.create');
    }

    public function store(ManageTicketRequest $request)
    {
        DB::beginTransaction();
        try {
            $days = Category::find($request->category_id)->days ?? Variable::where('cod', 'TKT_DAYS')->first()->concept;

            Ticket::create([
                'date' => $request->date,
                'date2' => Carbon::now()->addDays($days),
                'name' => $request->name,
                'company_id' => Auth::user()->current_company_id,
                'location_id' => $request->location_id,
                'category_id' => $request->category_id,
                'category2_id' => $request->category2_id,
                'item_id' => $request->item_id,
                'text' => $request->text,
                'state' => 2,
                'user_id' => Auth::user()->id,
                'user2_id' => $request->user2_id,
            ]);

            DB::commit();
            return redirect()->route('manage-ticket.index')->with('success', 'El ticket ha sido creado y asignado correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Ha ocurrido un error, por favor reportar con el siguiente mensaje: '.$e->getMessage());
        }
    }

    public function edit(Ticket $manage_ticket)
    {
        return view('ticket.manage.edit', compact('manage_ticket'));
    }

    public function update(ManageTicketRequest $request, Ticket $manage_ticket)
    {
        DB::beginTransaction();
        try {
            $days = Category::find($request->category_id)->days ?? Variable::where('cod', 'TKT_DAYS')->first()->concept;

            $manage_ticket->update([
                'date' => $request->date,
                'date2' => Carbon::now()->addDays($days),
                'name' => $request->name,
                'location_id' => $request->location_id,
                'category_id' => $request->category_id,
                'category2_id' => $request->category2_id,
                'item_id' => $request->item_id,
                'text' => $request->text,
                'state' => 2,
                'user2_id' => $request->user2_id,
            ]);

            DB::commit();
            return redirect()->route('manage-ticket.index')->with('success', 'El ticket ha sido actualizado y asignado correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Ha ocurrido un error, por favor reportar con el siguiente mensaje: '.$e->getMessage());
        }
    }

    public function destroy(Ticket $manage_ticket)
    {
        DB::beginTransaction();
        try {
            $manage_ticket->update(['state' => 1]);
            
            Tracking::create([
                'ticket_id' => $manage_ticket->id,
                'date' => Carbon::now(),
                'state' => 1,
                'user_id' => Auth::user()->id,
                'text' => 'Ticket eliminado por '.Auth::user()->name,
                'type' => 3,
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Ha ocurrido un error, por favor reportar con el siguiente mensaje: '.$e->getMessage());
        }
        
        return redirect()->route('manage-ticket.index')->with('success', 'El ticket ha sido eliminado correctamente.');
    }
}
