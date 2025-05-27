<?php

namespace App\Http\Controllers\Ticket;

use App\Http\Controllers\Controller;
use App\Http\Requests\Ticket\TrackingRequest;
use App\Mail\NotificationTicketResolve;
use App\Models\Config\Variable;
use App\Models\Ticket\Ticket;
use App\Models\Ticket\Tracking;
use App\Models\User;
use App\Services\TelegramCodeService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

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

            //type is ['0' => 'Seguimiento', '1' => 'Solución', '2' => 'Feedback', '3' => 'Cancelar Ticket']
            $type = $request->type == 1 ? 'Solución' : ($request->type == 2 ? 'Feedback' : ($request->type == 3 ? 'Cancelar Ticket' : 'Seguimiento'));
            $user = User::find($ticket->user_id);
            if (Variable::where('cod', 'TKT_NTF_EM')->first()->concept == 1) {
                $data = [
                    'numTicket' => $ticket->id,
                    'company' => $ticket->company->name,
                    'location' => $ticket->location->name,
                    'category' => $ticket->category->name,
                    'category2' => $ticket->category2->name,
                    'text' => $ticket->text,
                    'type' => $type,
                    'resolve' => $request->text,
                ];

                Mail::to($user->email)->send(new NotificationTicketResolve($data));
            }

            if (Variable::where('cod', 'TKT_NTF_TG')->first()->concept == 1)
            {
                if ($user->telegram_chat_id) 
                {
                    $telegramService = app(TelegramCodeService::class);
                    $icon = $request->type == 1 ? '✅' : ($request->type == 2 ? '⭐' : ($request->type == 3 ? '❌' : '📝'));
                    $telegramService->sendTelegramMessage($user->telegram_chat_id, "🎫 Ticket actualizado: #".$ticket->id." - ".$ticket->company->name
                        ."\n📍 Ubicación: ".$ticket->location->name
                        ."\n📂 Subcategoría: ".$ticket->category2->name
                        ."\n📄 Descripción: ".$ticket->text
                        ."\n📅 Fecha límite: ".$ticket->date2
                        ."\n "
                        ."\n*** Seguimiento"
                        ."\n".$icon." Tipo: ".$type
                        ."\n📢 Detalle: ".$request->text
                        ."\n🔗 ".route('ticket.show', $ticket->id));
                }                
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

            //type is ['0' => 'Seguimiento', '1' => 'Solución', '2' => 'Feedback', '3' => 'Cancelar Ticket']
            $type = $request->type == 1 ? 'Solución' : ($request->type == 2 ? 'Feedback' : ($request->type == 3 ? 'Cancelar Ticket' : 'Seguimiento'));
            $user = User::find($ticket->user_id);
            if (Variable::where('cod', 'TKT_NTF_EM')->first()->concept == 1) {
                $data = [
                    'numTicket' => $ticket->id,
                    'company' => $ticket->company->name,
                    'location' => $ticket->location->name,
                    'category' => $ticket->category->name,
                    'category2' => $ticket->category2->name,
                    'text' => $ticket->text,
                    'type' => $type,
                    'resolve' => $request->text,
                ];

                Mail::to($user->email)->send(new NotificationTicketResolve($data));
            }

            if (Variable::where('cod', 'TKT_NTF_TG')->first()->concept == 1)
            {
                if ($user->telegram_chat_id) 
                {
                    $telegramService = app(TelegramCodeService::class);
                    $icon = $request->type == 1 ? '✅' : ($request->type == 2 ? '⭐' : ($request->type == 3 ? '❌' : '📝'));
                    $telegramService->sendTelegramMessage($user->telegram_chat_id, "🎫 Ticket actualizado [Edición]: #".$ticket->id." - ".$ticket->company->name
                        ."\n📍 Ubicación: ".$ticket->location->name
                        ."\n📂 Subcategoría: ".$ticket->category2->name
                        ."\n📄 Descripción: ".$ticket->text
                        ."\n📅 Fecha límite: ".$ticket->date2
                        ."\n "
                        ."\n*** Seguimiento"
                        ."\n".$icon." Tipo: ".$type
                        ."\n❌ Detalle Anterior: ".$resolve_2ticket->text
                        ."\n📢 Detalle Actual: ".$request->text
                        ."\n🔗 ".route('ticket.show', $ticket->id));
                }                
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

        $user = User::find($ticket->user_id);
        if (Variable::where('cod', 'TKT_NTF_EM')->first()->concept == 1) {
            $data = [
                'numTicket' => $ticket->id,
                'company' => $ticket->company->name,
                'location' => $ticket->location->name,
                'category' => $ticket->category->name,
                'category2' => $ticket->category2->name,
                'text' => $ticket->text,
                'type' => 'Comentario Eliminado',
                'resolve' => "Se ha eliminado el seguimiento: ".$resolve_2ticket->text,
            ];

            Mail::to($user->email)->send(new NotificationTicketResolve($data));
        }

        if (Variable::where('cod', 'TKT_NTF_TG')->first()->concept == 1)
        {
            if ($user->telegram_chat_id) 
            {
                $telegramService = app(TelegramCodeService::class);
                $telegramService->sendTelegramMessage($user->telegram_chat_id, "🎫 Ticket actualizado [Eliminación]: #".$ticket->id." - ".$ticket->company->name
                    ."\n📍 Ubicación: ".$ticket->location->name
                    ."\n📂 Subcategoría: ".$ticket->category2->name
                    ."\n📄 Descripción: ".$ticket->text
                    ."\n📅 Fecha límite: ".$ticket->date2
                    ."\n "
                    ."\n*** Seguimiento"
                    ."\n❌ Tipo: Comentario Eliminado"
                    ."\n📢 Detalle Anterior: ".$resolve_2ticket->text
                    ."\n🔗 ".route('ticket.show', $ticket->id));
            }                
        }
        
        return redirect()->route('resolve-2ticket.index')->with('success', 'El seguimiento ha sido eliminado correctamente.');
    }

    public function attachment(Ticket $ticket, Tracking $resolve_2ticket)
    {
        return view('ticket.tracking2.attachment', compact('ticket', 'resolve_2ticket'));
    }
}
