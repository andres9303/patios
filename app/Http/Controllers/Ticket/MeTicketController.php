<?php

namespace App\Http\Controllers\Ticket;

use App\Http\Controllers\Controller;
use App\Http\Requests\Ticket\MeTicketRequest;
use App\Mail\NotificationTicketUser;
use App\Models\Config\Item;
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

class MeTicketController extends Controller
{
    public function index()
    {
        return view('ticket.me.index');
    }

    public function create()
    {
        return view('ticket.me.create');
    }

    public function store(MeTicketRequest $request)
    {
        DB::beginTransaction();
        try {
            $days = (int)Item::find($request->item_id)->factor ?? Variable::where('cod', 'TKT_DAYS')->first()->concept;

            $ticket = Ticket::create([
                'date' => $request->date,
                'date2' => Carbon::parse($request->date)->addDays($days),
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

            $user2 = User::find($request->user2_id);
            if (Variable::where('cod', 'TKT_NTF_EM')->first()->concept == 1) {
                $data = [
                    'numTicket' => $ticket->id,
                    'company' => $ticket->company->name,
                    'location' => $ticket->location->name,
                    'category' => $ticket->category->name,
                    'category2' => $ticket->category2->name,
                    'name' => $ticket->name,
                    'text' => $ticket->text,
                ];

                Mail::to($user2->email)->send(new NotificationTicketUser($data));
            }

            if (Variable::where('cod', 'TKT_NTF_TG')->first()->concept == 1)
            {
                if (User::find($request->user2_id)->telegram_chat_id) 
                {
                    $telegramService = app(TelegramCodeService::class);
                    $telegramService->sendTelegramMessage($user2->telegram_chat_id, "🎫 Nuevo ticket asignado: #".$ticket->id." - ".$ticket->company->name
                        ."\n📍 Ubicación: ".$ticket->location->name
                        ."\n📂 Subcategoría: ".$ticket->category2->name
                        ."\n📄 Descripción: ".$ticket->text
                        ."\n📅 Fecha límite: ".$ticket->date2
                        ."\n🔗 ".route('ticket.show', $ticket->id));
                }                
            }

            DB::commit();            

            return redirect()->route('me-ticket.index')->with('success', 'El ticket ha sido creado y asignado correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Ha ocurrido un error, por favor reportar con el siguiente mensaje: '.$e->getMessage());
        }
    }

    public function edit(Ticket $me_ticket)
    {
        return view('ticket.me.edit', compact('me_ticket'));
    }

    public function update(MeTicketRequest $request, Ticket $me_ticket)
    {
        DB::beginTransaction();
        try {
            $days = (int)Item::find($request->item_id)->factor ?? Variable::where('cod', 'TKT_DAYS')->first()->concept;

            $me_ticket->update([
                'date' => $request->date,
                'date2' => Carbon::parse($request->date)->addDays($days),
                'name' => $request->name,
                'location_id' => $request->location_id,
                'category_id' => $request->category_id,
                'category2_id' => $request->category2_id,
                'item_id' => $request->item_id,
                'text' => $request->text,
                'state' => 2,
                'user2_id' => $request->user2_id,
            ]);

            if (Variable::where('cod', 'TKT_NTF_EM')->first()->concept == 1) {
                $user2 = User::find($request->user2_id);

                $data = [
                    'numTicket' => $me_ticket->id,
                    'company' => $me_ticket->company->name,
                    'location' => $me_ticket->location->name,
                    'category' => $me_ticket->category->name,
                    'category2' => $me_ticket->category2->name,
                    'name' => $me_ticket->name,
                    'text' => $me_ticket->text,
                ];

                Mail::to($user2->email)->send(new NotificationTicketUser($data));
            }

            if (Variable::where('cod', 'TKT_NTF_TG')->first()->concept == 1)
            {
                $user2 = User::find($request->user2_id);
                if ($user2->telegram_chat_id) 
                {
                    $telegramService = app(TelegramCodeService::class);
                    $telegramService->sendTelegramMessage($user2->telegram_chat_id, "🎫 Nuevo ticket asignado: #".$me_ticket->id." - ".$me_ticket->company->name
                        ."\n📍 Ubicación: ".$me_ticket->location->name
                        ."\n📂 Subcategoría: ".$me_ticket->category2->name
                        ."\n📄 Descripción: ".$me_ticket->text
                        ."\n📅 Fecha límite: ".$me_ticket->date2
                        ."\n🔗 ".route('ticket.show', $me_ticket->id));
                }
                
            }

            DB::commit();

            return redirect()->route('me-ticket.index')->with('success', 'El ticket ha sido actualizado y asignado correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Ha ocurrido un error, por favor reportar con el siguiente mensaje: '.$e->getMessage());
        }
    }

    public function destroy(Ticket $me_ticket)
    {
        DB::beginTransaction();
        try {
            $me_ticket->update(['state' => 1]);
            
            Tracking::create([
                'ticket_id' => $me_ticket->id,
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
        
        return redirect()->route('me-ticket.index')->with('success', 'El ticket ha sido eliminado correctamente.');
    }

    public function attachment(Ticket $me_ticket)
    {
        $ticket = Ticket::find($me_ticket->id); 
        $url = 'me-ticket';
        return view('ticket.ticket.attachment', compact('ticket', 'url'));
    }
}
