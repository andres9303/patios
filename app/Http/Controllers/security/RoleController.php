<?php

namespace App\Http\Controllers\security;

use App\Http\Controllers\Controller;
use App\Http\Requests\Security\RoleRequest;
use App\Http\Requests\Security\PermissionRequest;
use App\Models\Security\Menu;
use App\Models\Security\Permission;
use App\Models\Security\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index()
    {
        return view('security.role.index');
    }

    public function create()
    {
        return view('security.role.create');
    }

    public function store(RoleRequest $request)
    {
        Role::create($request->validated());

        return redirect()->route('role.index')->with('success', 'Se ha registrado el grupo correctamente.');
    }

    public function edit(Role $role)
    {
        return view('security.role.edit', compact('role'));
    }

    public function update(RoleRequest $request, Role $role)
    {
        $role->update($request->validated());

        return redirect()->route('role.index')->with('success', 'Se ha actualizado la información del grupo correctamente.');
    }

    public function destroy(Role $role)
    {
        $role->delete();

        return redirect()->route('role.index')->with('success', 'Se ha eliminado el grupo correctamente.');
    }

    public function indexPermission()
    {
        return view('security.permission.index');
    }

    public function createPermission()
    {
        $roles = Role::all();
        $menus = Menu::all();
        $permissions = Permission::all();

        return view('security.permission.create', compact('roles', 'menus', 'permissions'));
    }

    public function storePermission(PermissionRequest $request)
    {
        $request->validated();

        $role = Role::find($request->role_id);

        if ($role->permissions()->where('permission_id', $request->permission_id)->where('menu_id', $request->menu_id)->exists()) {
            session()->flash('error', 'El permiso ya existe para este menú.');
            return redirect()->back();
        }

        $role->permissions()->attach($request->permission_id, ['menu_id' => $request->menu_id]);

        return redirect()->route('permission.index')->with('success', 'Se ha registrado el grupo correctamente.');
    }

    public function destroyPermission(Role $role, Menu $menu, Permission $permission)
    {
        $role->permissions()
            ->wherePivot('menu_id', $menu->id)
            ->detach($permission->id);

        return redirect()->route('permission.index')->with('success', 'Se ha eliminado el permiso correctamente.');
    }

    public function indexShortcut()
    {
        return view('security.shortcut.index');
    }

    public function createShortcut()
    {
        $roles = Role::all();
        $menus = Menu::whereNotNull('menu_id')->get();

        return view('security.shortcut.create', compact('roles', 'menus'));
    }

    public function storeShortcut(Request $request)
    {
        $request->validate([
            'role_id' => 'required|exists:roles,id',
            'menu_id' => 'required|exists:menus,id',
        ]);

        $role = Role::find($request->role_id);

        if ($role->shortcuts()->where('menu_role.menu_id', $request->menu_id)->exists()) {
            session()->flash('error', 'El acceso directo ya existe para este grupo.');
            return redirect()->back();
        }

        $role->shortcuts()->attach($request->menu_id);

        return redirect()->route('shortcut.index')->with('success', 'Se ha registrado el acceso directo correctamente.');
    }

    public function destroyShortcut(Role $role, Menu $menu)
    {
        $role->shortcuts()
            ->detach($menu->id);

        return redirect()->route('shortcut.index')->with('success', 'Se ha eliminado el acceso directo correctamente.');
    }
}

