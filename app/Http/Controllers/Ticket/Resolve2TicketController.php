<?php

namespace App\Http\Controllers\Ticket;

use App\Http\Controllers\Controller;
use App\Http\Requests\Ticket\TrackingRequest;
use App\Models\Ticket\Ticket;
use App\Models\Ticket\Tracking;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Resolve2TicketController extends Controller
{
    public function index()
    {
        return view('ticket.tracking2.index');
    }

    public function create(Ticket $ticket)
    {
        return view('ticket.tracking2.create', compact('ticket'));
    }

    public function store(TrackingRequest $request, Ticket $ticket)
    {
        DB::beginTransaction();
        try {
            Tracking::create([
                'ticket_id' => $ticket->id,
                'user_id' => Auth::user()->id,
                'text' => $request->text,
                'date' => $request->date,
                'state' => 1,
                'type' => $request->type ?? 0,
            ]);

            if ($request->type == 1 || $request->type == 3) {
                $ticket->update([
                    'state' => 1,
                    'date3' => Carbon::now(),
                ]);
            }

            DB::commit();
            return redirect()->route('resolve-2ticket.index')->with('success', 'El seguimiento ha sido registrado correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Ha ocurrido un error, por favor reportar con el siguiente mensaje: '.$e->getMessage());
        }
    }

    public function edit(Ticket $ticket, Tracking $resolve_2ticket)
    {
        return view('ticket.tracking2.edit', compact('resolve_2ticket', 'ticket'));
    }

    public function update(TrackingRequest $request, Ticket $ticket, Tracking $resolve_2ticket)
    {
        DB::beginTransaction();
        try {
            $resolve_2ticket->update([
                'text' => $request->text,
                'date' => $request->date,
                'type' => $request->type ?? 0,
            ]);

            if ($request->type == 1 || $request->type == 3) {
                $ticket->update([
                    'state' => 1,
                    'date3' => Carbon::now(),
                ]);
            }

            DB::commit();
            return redirect()->route('resolve-2ticket.index')->with('success', 'El seguimiento ha sido actualizado correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Ha ocurrido un error, por favor reportar con el siguiente mensaje: '.$e->getMessage());
        }
    }

    public function destroy(Ticket $ticket, Tracking $resolve_2ticket)
    {
        $resolve_2ticket->update([
            'state' => 0,
        ]);
        
        return redirect()->route('resolve-2ticket.index')->with('success', 'El seguimiento ha sido eliminado correctamente.');
    }

    public function attachment(Ticket $ticket, Tracking $resolve_2ticket)
    {
        return view('ticket.tracking2.attachment', compact('ticket', 'resolve_2ticket'));
    }
}
