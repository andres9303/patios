<?php

namespace App\Http\Controllers\master;

use App\Http\Controllers\Controller;
use App\Http\Requests\Master\CategoryProductRequest;
use App\Models\Config\Item;
use App\Models\Master\Product;
use Illuminate\Support\Facades\DB;

class CategoryProductController extends Controller
{
    public function index()
    {
        return view('master.category-product.index');
    }

    public function create()
    {
        return view('master.category-product.create');
    }

    public function store(CategoryProductRequest $request)
    {
        DB::beginTransaction();
        try {
            Item::create([
                'name' => $request->name,
                'text' => $request->text,
                'order' => $request->order,
                'catalog_id' => 203, 
            ]);

            DB::commit();
            return redirect()->route('category-product.index')->with('success', 'Se ha registrado la categoría de producto correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Ha ocurrido un error, por favor reportar con el siguiente mensaje: '.$e->getMessage());
        }
    }

    public function edit(Item $categories_product)
    {
        return view('master.category-product.edit', compact('categories_product'));
    }

    public function update(CategoryProductRequest $request, Item $categories_product)
    {
        DB::beginTransaction();
        try {
            $categories_product->update([
                'name' => $request->name,
                'text' => $request->text,
                'order' => $request->order,
            ]);

            DB::commit();
            return redirect()->route('category-product.index')->with('success', 'Se ha actualizado la categoría de producto correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Ha ocurrido un error, por favor reportar con el siguiente mensaje: '.$e->getMessage());
        }
    }

    public function destroy(Item $categories_product)
    {
        if (Product::where('item_id', $categories_product->id)->exists()) {
            return redirect()->route('category-product.index')
                ->with('error', 'No se puede eliminar la categpría porque tiene productos asociados.');
        }

        DB::beginTransaction();
        try {
            $categories_product->delete();
            DB::commit();
            return redirect()->route('category-product.index')->with('success', 'Se ha eliminado la categoría de producto correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Ha ocurrido un error, por favor reportar con el siguiente mensaje: '.$e->getMessage());
        }
    }    
}


