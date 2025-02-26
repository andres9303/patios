<?php

namespace App\Http\Controllers\cost;

use App\Http\Controllers\Controller;
use App\Http\Requests\cost\DirectPurchaseRequest;
use App\Models\Config\Item;
use App\Models\Doc;
use App\Models\Master\Product;
use App\Models\Mvto;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DirectPurchaseController extends Controller
{
    private $menuId = 502;

    public function index()
    {
        return view('cost.direct_purchase.index');
    }

    public function create()
    {
        $menuId = $this->menuId;
        $products = $products = Product::where('state', 1)
                        ->where('isinventory', 1)
                        ->get();
        $categories = Item::where('catalog_id', 203)->get();

        return view('cost.direct_purchase.create', compact('menuId', 'products', 'categories'));
    }

    public function store(DirectPurchaseRequest $request)
    {
        DB::beginTransaction();
        try {
            $orders = Order::where('menu_id', $this->menuId,)
                ->where('company_id', Auth::user()->current_company_id)
                ->where('user_id', Auth::id())
                ->get();

            if ($orders->isEmpty()) {
                return back()->withInput()->with('error', 'No se ha registrado ningún producto en la factura.');
            }

            foreach ($orders as $order) {
                if (!$order->product_id) {
                    return back()->withInput()->with('error', 'No ha seleccionado correctamente los productos.');
                }
            }

            if ($orders->where('doc_id')->isNotEmpty()) {
                $doc = Doc::find($orders->first()->doc_id);
                $doc->update([
                    'code' => $request->code,
                    'num' => $request->num,
                    'date' => $request->date,
                    'person_id' => $request->person_id,
                    'subtotal' => $orders->sum(function ($order) {
                                    return $order->cant * $order->value;
                                }),
                    'iva' => $orders->sum(function ($order) {
                                    return $order->cant * $order->value * $order->iva / 100;
                                }),
                    'total' => $orders->sum(function ($order) {
                                    return $order->cant * $order->value * (1 + $order->iva / 100);
                                }),
                    'state' => 1,
                    'user_id' => Auth::id(),
                ]);

                Mvto::where('doc_id', $doc->id)->update([
                    'cant' => 0,
                    'valueu' => 0,
                    'iva' => 0,
                    'valuet' => 0,
                    'cant2' => 0,
                    'valueu2' => 0,
                    'iva2' => 0,
                    'valuet2' => 0,
                    'state' => 0,
                ]);
            }
            else
            {
                $doc = Doc::create([
                    'menu_id' => $this->menuId,
                    'company_id' => Auth::user()->current_company_id,
                    'code' => $request->code,
                    'num' => $request->num,
                    'date' => $request->date,
                    'person_id' => $request->person_id,
                    'subtotal' => $orders->sum(function ($order) {
                                    return $order->cant * $order->value;
                                }),
                    'iva' => $orders->sum(function ($order) {
                                    return $order->cant * $order->value * $order->iva / 100;
                                }),
                    'total' => $orders->sum(function ($order) {
                                    return $order->cant * $order->value * (1 + $order->iva / 100);
                                }),
                    'state' => 1,
                    'user_id' => Auth::id(),
                ]);
            }            

            foreach ($orders as $order) {
                Mvto::create([
                    'doc_id' => $doc->id,
                    'product_id' => $order->product_id,
                    'unit_id' => $order->unit_id,
                    'cant' => $order->cant,
                    'valueu' => $order->value,
                    'iva' => $order->iva,
                    'valuet' => $order->cant * $order->value * (1 + $order->iva / 100),                    
                    'product2_id' => $order->product_id,
                    'unit2_id' => $order->unit_id,
                    'cant2' => $order->cant,
                    'valueu2' => $order->value,
                    'iva2' => $order->iva,
                    'valuet2' => $order->cant * $order->value * (1 + $order->iva / 100),
                    'state' => 1,
                ]);
            }
            
            Order::where('menu_id', $this->menuId,)
                ->where('company_id', Auth::user()->current_company_id)
                ->where('user_id', Auth::id())
                ->delete();

            DB::commit();
            return redirect()->route('direct-purchase.index')->with('success', 'Se ha registrado la factura correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Ha ocurrido un error, por favor reportar con el siguiente mensaje: '.$e->getMessage());
        }
    }

    public function edit(Doc $direct_purchase)
    {
        $menuId = $this->menuId;
        $products = $products = Product::where('state', 1)
                        ->where('isinventory', 1)
                        ->get();
        $categories = Item::where('catalog_id', 203)->get();

        DB::beginTransaction();
        try {
            Order::where('menu_id', $this->menuId)
            ->where('company_id', Auth::user()->current_company_id)
            ->where('user_id', Auth::id())
            ->delete();

            foreach ($direct_purchase->mvtos as $order) {
                Order::create([
                    'doc_id' => $direct_purchase->id,
                    'menu_id' => $this->menuId,
                    'company_id' => Auth::user()->current_company_id,
                    'product_id' => $order->product_id,
                    'unit_id' => $order->unit_id,
                    'cant' => $order->cant > 0 ? $order->cant : $order->cant * -1,
                    'value' => $order->valueu,
                    'iva' => $order->iva,
                    'user_id' => Auth::id(),
                ]);
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Ha ocurrido un error, por favor reportar con el siguiente mensaje: '.$e->getMessage());
        }

        return view('cost.direct_purchase.edit', compact('menuId', 'products', 'categories', 'direct_purchase'));
    }

    public function destroy(Doc $direct_purchase)
    {
        DB::beginTransaction();
        try {
            $direct_purchase->update(['code' => 'ANULADO_'.$direct_purchase->code,
                'subtotal' => 0,
                'iva' => 0,
                'total' => 0,
                'state' => 0
            ]);
            $direct_purchase->mvtos()->update([
                'cant' => 0,
                'valueu' => 0,
                'iva' => 0,
                'valuet' => 0,
                'cant2' => 0,
                'value2' => 0,
                'iva2' => 0,
                'valuet2' => 0,
                'state' => 0,
            ]);
            DB::commit();
            return redirect()->route('direct-purchase.index')->with('success', 'Se ha eliminado la factura correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Ha ocurrido un error, por favor reportar con el siguiente mensaje: '.$e->getMessage());
        }
    }
}
