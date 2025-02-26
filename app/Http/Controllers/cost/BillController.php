<?php

namespace App\Http\Controllers\cost;

use App\Http\Controllers\Controller;
use App\Http\Requests\cost\BillRequest;
use App\Models\Config\Item;
use App\Models\Doc;
use App\Models\Master\Product;
use App\Models\Mvto;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BillController extends Controller
{
    private $menuId = 501;

    public function index()
    {
        return view('cost.bill.index');
    }

    public function create()
    {
        $menuId = $this->menuId;
        $products = $products = Product::where('state', 1)
                        ->where('isinventory', 0)
                        ->get();
        $categories = Item::where('catalog_id', 203)->get();

        return view('cost.bill.create', compact('menuId', 'products', 'categories'));
    }

    public function store(BillRequest $request)
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
                if (!$order->product_id || !$order->activity_id) {
                    return back()->withInput()->with('error', 'Todos los productos deben tener una actividad asignada.');
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
                    'costu' => 0,
                    'state' => 0,
                    'activity_id' => null,
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
                    'costu' => $order->value,
                    'state' => 1,
                    'activity_id' => $order->activity_id,
                ]);
            }
            
            Order::where('menu_id', $this->menuId,)
                ->where('company_id', Auth::user()->current_company_id)
                ->where('user_id', Auth::id())
                ->delete();

            DB::commit();
            return redirect()->route('bill.index')->with('success', 'Se ha registrado la factura correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Ha ocurrido un error, por favor reportar con el siguiente mensaje: '.$e->getMessage());
        }
    }

    public function edit(Doc $bill)
    {
        $menuId = $this->menuId;
        $products = $products = Product::where('state', 1)
                        ->where('isinventory', 0)
                        ->get();
        $categories = Item::where('catalog_id', 203)->get();

        DB::beginTransaction();
        try {
            Order::where('menu_id', $this->menuId)
            ->where('company_id', Auth::user()->current_company_id)
            ->where('user_id', Auth::id())
            ->delete();

            foreach ($bill->mvtos as $order) {
                Order::create([
                    'doc_id' => $bill->id,
                    'menu_id' => $this->menuId,
                    'company_id' => Auth::user()->current_company_id,
                    'product_id' => $order->product_id,
                    'unit_id' => $order->unit_id,
                    'cant' => $order->cant > 0 ? $order->cant : $order->cant * -1,
                    'value' => $order->valueu,
                    'iva' => $order->iva,
                    'activity_id' => $order->activity_id,
                    'user_id' => Auth::id(),
                ]);
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Ha ocurrido un error, por favor reportar con el siguiente mensaje: '.$e->getMessage());
        }

        return view('cost.bill.edit', compact('menuId', 'products', 'categories', 'bill'));
    }

    public function destroy(Doc $bill)
    {
        DB::beginTransaction();
        try {
            $bill->update(['code' => 'ANULADO_'.$bill->code,
                'subtotal' => 0,
                'iva' => 0,
                'total' => 0,
                'state' => 0
            ]);
            $bill->mvtos()->update(['cant' => 0,
                    'valueu' => 0,
                    'iva' => 0,
                    'valuet' => 0,
                    'costu' => 0,
                    'state' => 0,
                    'activity_id' => null,
                ]);
            DB::commit();
            return redirect()->route('bill.index')->with('success', 'Se ha eliminado la factura correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Ha ocurrido un error, por favor reportar con el siguiente mensaje: '.$e->getMessage());
        }
    }
}
