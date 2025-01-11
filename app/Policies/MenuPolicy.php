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

        if ($menu) {
            $companyAllId = Company::where('name', 'Todos')->value('id');
            $companyIds = [$user->current_company_id, $companyAllId];

            $hasAccess = $user->rolesInCompanies($companyIds)
                ->whereHas('menus', function ($query) use ($menu) {
                    $query->where('menus.id', $menu->id);
                })
                ->exists();

            return $hasAccess;
        }

        return false;
    }

    public function approve1(User $user, $menuRoute): bool
    {
        $menu = Menu::where('route', $menuRoute)->first();

        if ($menu) {
            $companyAllId = Company::where('name', 'Todos')->value('id');
            $companyIds = [$user->current_company_id, $companyAllId];

            $hasPermission = $user->rolesInCompanies($companyIds)
                ->whereHas('permissions', function ($query) use ($menu) {
                    $query->where('permission_role.menu_id', $menu->id)
                        ->where('permission_role.permission_id', 7); // 7: Aprobar 1
                })
                ->exists();

            return $hasPermission;
        }

        return false;
    }

    public function approve2(User $user, $menuRoute): bool
    {
        $menu = Menu::where('route', $menuRoute)->first();

        if ($menu) {
            $companyAllId = Company::where('name', 'Todos')->value('id');
            $companyIds = [$user->current_company_id, $companyAllId];

            $hasPermission = $user->rolesInCompanies($companyIds)
                ->whereHas('permissions', function ($query) use ($menu) {
                    $query->where('permission_role.menu_id', $menu->id)
                        ->where('permission_role.permission_id', 8); // 8: Aprobar 2
                })
                ->exists();

            return $hasPermission;
        }

        return false;
    }
}
