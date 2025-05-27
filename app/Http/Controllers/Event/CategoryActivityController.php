<?php

namespace App\Http\Controllers\Event;

use App\Http\Controllers\Controller;
use App\Http\Requests\Event\CategoryActivityRequest;
use App\Models\Config\Item;
use Illuminate\Support\Facades\DB;

class CategoryActivityController extends Controller
{
    private int $catalog_id = 70001;

    public function index()
    {
        return view('event.category-activity.index');
    }

    public function create()
    {
        return view('event.category-activity.create');
    }

    public function store(CategoryActivityRequest $request)
    {
        DB::beginTransaction();
        try {
            Item::create([
                'name' => $request->name,
                'text' => $request->text,
                'order' => $request->order,
                'catalog_id' => $this->catalog_id,
            ]);

            DB::commit();
            return redirect()->route('category-activity.index')->with('success', 'Se ha registrado la categoría de actividad correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Ha ocurrido un error, por favor reportar con el siguiente mensaje: '.$e->getMessage());
        }
    }

    public function edit(Item $category_activity)
    {
        return view('event.category-activity.edit', compact('category_activity'));
    }

    public function update(CategoryActivityRequest $request, Item $category_activity)
    {
        DB::beginTransaction();
        try {
            $category_activity->update([
                'name' => $request->name,
                'text' => $request->text,
                'order' => $request->order,
            ]);

            DB::commit();
            return redirect()->route('category-activity.index')->with('success', 'Se ha actualizado la categoría de actividad correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Ha ocurrido un error, por favor reportar con el siguiente mensaje: '.$e->getMessage());
        }
    }

    public function destroy(Item $category_activity)
    {
        DB::beginTransaction();
        try {
            $category_activity->delete();
            DB::commit();
            return redirect()->route('category-activity.index')->with('success', 'Se ha eliminado la categoría de actividad correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Ha ocurrido un error, por favor reportar con el siguiente mensaje: '.$e->getMessage());
        }
    }
}
