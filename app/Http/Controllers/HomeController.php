<?php

namespace App\Http\Controllers;

use App\Models\Config\Variable;
use App\Models\Security\Menu;
use App\Models\Ticket\Ticket;
use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {

        $user = User::find(auth()->user()->id);
        $roles = $user->roles()->wherePivotIn('company_id', [1, $user->current_company_id])->get();

        $shortcuts = Menu::whereIn('id', function($query) use ($roles) {
                            $query->select('menu_id')
                                ->from('menu_role')
                                ->whereIn('role_id', $roles->pluck('id'));
                        })->distinct()->get();

        $tracking2 = Variable::where('cod', 'TKT_TYPE2')->first()->concept;
        $tracking3 = Variable::where('cod', 'TKT_TYPE3')->first()->concept;
        
        $resolve_tickets = Ticket::query()
                            ->where('tickets.company_id', auth()->user()->current_company_id)
                            ->where('tickets.user2_id', auth()->user()->id)
                            ->where('tickets.state', '<>', 0)
                            ->whereNotIn('tickets.category2_id', [$tracking2, $tracking3])
                            ->count();
                            
        $resolve2_tickets = Ticket::query()
                            ->where('tickets.company_id', auth()->user()->current_company_id)
                            ->where('tickets.user2_id', auth()->user()->id)
                            ->where('tickets.state', '<>', 0)
                            ->whereIn('tickets.category2_id', [$tracking2])
                            ->count();

        $resolve3_tickets = Ticket::query()
                            ->where('tickets.company_id', auth()->user()->current_company_id)
                            ->where('tickets.user2_id', auth()->user()->id)
                            ->where('tickets.state', '<>', 0)
                            ->whereIn('tickets.category2_id', [$tracking3])
                            ->count();
                        
        return view('dashboard', compact('shortcuts', 'resolve_tickets', 'resolve2_tickets', 'resolve3_tickets'));
    }
}
