<?php

namespace App\Http\Controllers\master;

use App\Http\Controllers\Controller;
use App\Http\Requests\Master\ProductRequest;
use App\Models\Config\Item;
use App\Models\Master\Product;
use App\Models\Master\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    private int $category_id = 203;
    private int $type_id = 20701;

    public function index()
    {
        return view('master.product.index');
    }

    public function create()
    {
        $units = Unit::where('state', 1)->get();
        $items = Item::where('catalog_id', $this->category_id)->get();
        $areas = Item::where('catalog_id', $this->type_id)->get();
        return view('master.product.create', compact('units', 'items', 'areas'));
    }

    public function store(ProductRequest $request)
    {
        DB::beginTransaction();
        try {
            $product = Product::create([
                'code' => $request->code,
                'name' => $request->name,
                'unit_id' => $request->unit_id,
                'state' => $request->state ?? 1,
                'isinventory' => $request->isinventory ?? false,
                'item_id' => $request->item_id,
                'class' => $request->class ?? 0,
                'type' => $request->type,
            ]);

            $product->companies()->sync($request->companies);

            DB::commit();
            return redirect()->route('product.index')->with('success', 'Se ha registrado el producto correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Ha ocurrido un error, por favor reportar con el siguiente mensaje: '.$e->getMessage());
        }
    }

    public function edit(Product $product)
    {
        $units = Unit::where('state', 1)->get();
        $items = Item::where('catalog_id', $this->category_id)->get();
        $areas = Item::where('catalog_id', $this->type_id)->get();
        return view('master.product.edit', compact('product', 'units', 'items', 'areas'));
    }

    public function update(ProductRequest $request, Product $product)
    {
        DB::beginTransaction();
        try {
            $product->update([
                'code' => $request->code,
                'name' => $request->name,
                'unit_id' => $request->unit_id,
                'state' => $request->state ?? 1,
                'isinventory' => $request->isinventory ?? false,
                'item_id' => $request->item_id,
                'class' => $request->class ?? 0,
                'type' => $request->type,
            ]);

            $product->companies()->sync($request->companies);

            DB::commit();
            return redirect()->route('product.index')->with('success', 'Se ha actualizado el producto correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Ha ocurrido un error, por favor reportar con el siguiente mensaje: '.$e->getMessage());
        }
    }

    public function destroy(Product $product)
    {
        $product->update(['state' => 0]);
        
        return redirect()->route('product.index')->with('success', 'Se ha eliminado el producto correctamente.');
    }
}
