<?php

namespace App\Policies;

use App\Models\Master\Company;
use App\Models\Security\Menu;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\DB;

class MenuPolicy
{
    
    public function view(User $user, $menu): bool
    {
        $menu = Menu::where('route', $menu)->first();
        if($menu)
        {
            $roles = $user->roles()->get();
            $roles = $roles->filter(function ($role) use ($user) {                
                $company_all = Company::where('name', 'Todos')->first();

                return $role->pivot->company_id == $user->current_company_id || $role->pivot->company_id == $company_all->id;
            });
            
            foreach ($roles as $role) {
                if ($role->menus->contains('id', $menu->id)) {
                    return true;
                }
            }
        }

        return false;
    }

    public function approve1(User $user, $name): bool
    {
        $menu = Menu::where('route', $name)->first();
        if($menu)
        {
            $roles = $user->roles()->get();
            $roles = $roles->filter(function ($role) use ($user) {
                $company_all = Company::where('name', 'Todos')->first();

                return $role->pivot->company_id == $user->current_company_id || $role->pivot->company_id == $company_all->id;
            });
            foreach ($roles as $role) {
                $exists = DB::table('permission_role')
                    ->where('role_id', $role->id)
                    ->where('menu_id', $menu->id)
                    ->where('permission_id', 7) // 7: Aprobar 1
                    ->exists();

                if ($exists) {
                    return true;
                }
            }
        }

        return false;
    }

    public function approve2(User $user, $name): bool
    {
        $menu = Menu::where('route', $name)->first();
        if($menu)
        {
            $roles = $user->roles()->get();
            $roles = $roles->filter(function ($role) use ($user) {
                $company_all = Company::where('name', 'Todos')->first();

                return $role->pivot->company_id == $user->current_company_id || $role->pivot->company_id == $company_all->id;
            });
            foreach ($roles as $role) {
                $exists = DB::table('permission_role')
                    ->where('role_id', $role->id)
                    ->where('menu_id', $menu->id)
                    ->where('permission_id', 8) // 8: Aprobar 2
                    ->exists();

                if ($exists) {
                    return true;
                }
            }
        }

        return false;
    }
}
