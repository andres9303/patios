<?php

namespace App\Http\Controllers\config;

use App\Http\Controllers\Controller;
use App\Http\Requests\Config\ListRequest;
use App\Models\Config\Catalog;
use App\Models\Config\Item;
use Illuminate\Support\Facades\DB;

class ListController extends Controller
{
    public function index()
    {
        return view('config.list.index');
    }

    public function create()
    {
        $catalogs = Catalog::all();
        $items = Item::all();

        return view('config.list.create', compact('catalogs', 'items'));
    }

    public function store(ListRequest $request)
    {
        DB::transaction(function () use ($request) {
            Item::create($request->validated());
        });

        return redirect()->route('list.index')->with('success', 'Se ha registrado el item de la lista correctamente.');
    }

    public function edit(Item $list)
    {
        $catalogs = Catalog::all();
        $items = Item::all();

        return view('config.list.edit', compact('list', 'catalogs', 'items'));
    }

    public function update(ListRequest $request, Item $list)
    {
        DB::transaction(function () use ($request, $list) {
            $list->fill($request->validated())->save();
        });

        return redirect()->route('list.index')->with('success', 'Se ha actualizado la información del item de la lista correctamente.');
    }

    public function destroy(Item $list)
    {
        DB::transaction(function () use ($list) {
            $list->delete();
        });

        return redirect()->route('list.index')->with('success', 'Se ha eliminado el item de la lista correctamente.');
    }
}
