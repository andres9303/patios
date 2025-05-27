<?php

namespace App\Http\Controllers\Ticket;

use App\Http\Controllers\Controller;
use App\Http\Requests\Ticket\ManageTicketRequest;
use App\Mail\NotificationTicketUser;
use App\Models\Config\Item;
use App\Models\Config\Variable;
use App\Models\Ticket\Ticket;
use App\Models\Ticket\Tracking;
use App\Models\User;
use App\Services\TelegramCodeService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

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
            $days = (int)Item::find($request->item_id)->factor ?? (Variable::where('cod', 'TKT_DAYS')->first()->concept ?? 0);

            $manage_ticket->update([
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
                    'numTicket' => $manage_ticket->id,
                    'company' => $manage_ticket->company->name,
                    'location' => $manage_ticket->location->name,
                    'category' => $manage_ticket->category->name,
                    'category2' => $manage_ticket->category2->name,
                    'name' => $manage_ticket->name,
                    'text' => $manage_ticket->text,
                ];

                Mail::to($user2->email)->send(new NotificationTicketUser($data));
            }

            if (Variable::where('cod', 'TKT_NTF_TG')->first()->concept == 1)
            {
                $user2 = User::find($request->user2_id);
                if ($user2->telegram_chat_id) 
                {
                    $telegramService = app(TelegramCodeService::class);
                    $telegramService->sendTelegramMessage($user2->telegram_chat_id, "🎫 Nuevo ticket asignado: #".$manage_ticket->id." - ".$manage_ticket->company->name
                        ."\n📍 Ubicación: ".$manage_ticket->location->name
                        ."\n📂 Subcategoría: ".$manage_ticket->category2->name
                        ."\n📄 Descripción: ".$manage_ticket->text
                        ."\n📅 Fecha límite: ".$manage_ticket->date2
                        ."\n🔗 ".route('ticket.show', $manage_ticket->id));
                }
                
            }

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

    public function attachment(Ticket $manage_ticket)
    {
        $ticket = Ticket::find($manage_ticket->id); 
        $url = 'manage-ticket';
        return view('ticket.ticket.attachment', compact('ticket', 'url'));
    }
}
