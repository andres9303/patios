<?php

namespace App\Http\Controllers\master;

use App\Http\Controllers\Controller;
use App\Http\Requests\Master\CategoryRequest;
use App\Models\Master\Category;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    public function index()
    {
        return view('master.category.index');
    }

    public function create()
    {
        $categories = Category::where('company_id', Auth::user()->current_company_id)
                                ->whereNull('ref_id')
                                ->where('state', 1)
                                ->get();
        return view('master.category.create', compact('categories'));
    }

    public function store(CategoryRequest $request)
    {
        DB::beginTransaction();
        try {
            Category::create([
                'code' => $request->code,
                'name' => $request->name,
                'text' => $request->text,
                'days' => $request->days,
                'ref_id' => $request->ref_id,
                'company_id' => Auth::user()->current_company_id,
                'state' => $request->state ?? 0,
            ]);

            DB::commit();
            return redirect()->route('category.index')->with('success', 'Se ha registrado la categoría correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Ha ocurrido un error, por favor reportar con el siguiente mensaje: '.$e->getMessage());
        }
    }

    public function edit(Category $category)
    {
        $categories = Category::where('company_id', Auth::user()->current_company_id)
                                    ->where('id', '!=', $category->id)
                                    ->whereNull('ref_id')
                                    ->where('state', 1)
                                    ->get();
        return view('master.category.edit', compact('category', 'categories'));
    }

    public function update(CategoryRequest $request, Category $category)
    {
        DB::beginTransaction();
        try {
            $category->update([
                'code' => $request->code,
                'name' => $request->name,
                'text' => $request->text,
                'days' => $request->days,
                'ref_id' => $request->ref_id,
                'company_id' => Auth::user()->current_company_id,
                'state' => $request->state ?? 0,
            ]);

            DB::commit();
            return redirect()->route('category.index')->with('success', 'Se ha actualizado la categoría correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Ha ocurrido un error, por favor reportar con el siguiente mensaje: '.$e->getMessage());
        }
    }

    public function destroy(Category $category)
    {
        $category->update(['state' => 0]);
        
        return redirect()->route('category.index')->with('success', 'Se ha eliminado la categoría correctamente.');
    }
}
