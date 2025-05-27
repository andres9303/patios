<?php

namespace App\Http\Controllers\security;

use App\Http\Controllers\Controller;
use App\Http\Requests\Security\MenuRequest;
use App\Models\Security\Menu;

class MenuController extends Controller
{
    public function index()
    {
        return view('security.menu.index');
    }

    public function create()
    {
        $menus = Menu::whereNull('menu_id')->get();

        return view('security.menu.create', compact('menus'));
    }

    public function store(MenuRequest $request)
    {
        Menu::create($request->validated() + [
            'active' => $request->input('active'), 
            'menu_id' => $request->input('menu_id'), 
        ]);

        return redirect()->route('menu.index')->with('success', 'Se ha registrado el formulario correctamente.');
    }

    public function edit(Menu $menu)
    {
        $menus = Menu::whereNull('menu_id')->get();

        return view('security.menu.edit', compact('menu', 'menus'));
    }

    public function update(MenuRequest $request, Menu $menu)
    {
        $menu->update($request->validated() + [
            'active' => $request->input('active'), 
            'menu_id' => $request->input('menu_id'), 
        ]);

        return redirect()->route('menu.index')->with('success', 'Se ha actualizado la información correctamente.');
    }

    public function destroy(Menu $menu)
    {
        $menu->delete();

        return redirect()->route('menu.index')->with('success', 'Se ha eliminado el formulario correctamente.');
    }
}
