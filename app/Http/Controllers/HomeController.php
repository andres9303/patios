<?php

namespace App\Http\Controllers;

use App\Models\Security\Menu;
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
                        
        return view('dashboard', compact('shortcuts'));
    }
}
