<?php

namespace App\Http\Controllers\cost;

use App\Http\Controllers\Controller;
use App\Http\Requests\cost\AssignRequest;
use App\Models\Config\Item;
use App\Models\Doc;
use App\Models\Master\Person;
use App\Models\Master\Product;
use App\Models\Mvto;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AssignController extends Controller
{
    private $menuId = 505;

    public function index()
    {
        return view('cost.assign.index');
    }

    public function create()
    {
        Order::where('menu_id', $this->menuId)
            ->where('company_id', Auth::user()->current_company_id)
            ->where('user_id', Auth::id())
            ->delete();

        $menuId = $this->menuId;
        $products = Product::where('state', 1)->where('isinventory', 1)->whereHas('companies', function ($query) {
            $query->where('company_id', Auth::user()->current_company_id);
        })->orderBy('name')->get();
        $categories = Item::where('catalog_id', 203)->orderBy('name')->get();
        $persons = Person::where('isEmployee', 1)->where('state', 1)->get();
        
        return view('cost.assign.create', compact('menuId', 'products', 'categories', 'persons'));
    }

    public function store(AssignRequest $request)
    {
        DB::beginTransaction();
        try {
            $orders = Order::where('menu_id', $this->menuId)
                ->where('company_id', Auth::user()->current_company_id)
                ->where('user_id', Auth::id())
                ->get();

            if ($orders->isEmpty()) {
                return back()->withInput()->with('error', 'No se ha registrado ningún producto en la asignación.');
            }

            if (isset($request->doc_id)) {
                $doc = Doc::find($request->doc_id);
                $doc->update([
                    'code' => $request->num ? ($request->code ?? 'ASIGN') : 'ASI',
                    'num' => $request->num ?? Doc::where('menu_id', $this->menuId)->where('company_id', Auth::user()->current_company_id)->max('num') + 1,
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
                    'text' => $request->text,
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
                    'costu' => 0,
                    'state' => 0,
                ]);
            }
            else
            {
                $doc = Doc::create([
                    'menu_id' => $this->menuId,
                    'company_id' => Auth::user()->current_company_id,
                    'code' => $request->num ? ($request->code ?? 'ASIGN') : 'ASI',
                    'num' => $request->num ?? Doc::where('menu_id', $this->menuId)->where('company_id', Auth::user()->current_company_id)->max('num') + 1,
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
                    'text' => $request->text,
                ]);
            }            

            foreach ($orders as $order) {
                $mvto = Mvto::where('doc_id', $order->doc_id)->where('product_id', $order->product_id)->where('unit_id', $order->unit_id)->where('state', 0)->first();
                if ($mvto) {
                    $mvto->update([
                        'cant' => $order->cant,
                        'valueu' => $order->value,
                        'iva' => $order->iva,
                        'valuet' => $order->cant * $order->value * (1 + $order->iva / 100),                    
                        'product2_id' => $order->product_id,
                        'unit2_id' => $order->unit_id,
                        'cant2' => $order->cant * -1,
                        'valueu2' => $order->value,
                        'valuet2' => $order->cant * $order->value * -1,
                        'costu' => $order->value,
                        'state' => 1,
                    ]);
                } else {
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
                        'cant2' => $order->cant * -1,
                        'valueu2' => $order->value,
                        'valuet2' => $order->cant * $order->value * -1,
                        'costu' => $order->value,
                        'state' => 1,
                    ]);
                }
            }
            
            Order::where('menu_id', $this->menuId,)
                ->where('company_id', Auth::user()->current_company_id)
                ->where('user_id', Auth::id())
                ->delete();

            DB::commit();
            return redirect()->route('assign.index')->with('success', 'Se ha registrado la asignación de insumos correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Ha ocurrido un error, por favor reportar con el siguiente mensaje: '.$e->getMessage());
        }
    }

    public function edit(Doc $assign)
    {
        $menuId = $this->menuId;
        $products = Product::where('state', 1)->where('isinventory', 1)->whereHas('companies', function ($query) {
            $query->where('company_id', Auth::user()->current_company_id);
        })->orderBy('name')->get();
        $categories = Item::where('catalog_id', 203)->orderBy('name')->get();
        $persons = Person::where('isEmployee', 1)->where('state', 1)->get();
        
        DB::beginTransaction();
        try {
            Order::where('menu_id', $this->menuId)
            ->where('company_id', Auth::user()->current_company_id)
            ->where('user_id', Auth::id())
            ->delete();

            foreach ($assign->mvtos as $order) {
                if ($order->state == 1 && $order->cant <> 0) 
                {
                    Order::create([
                        'doc_id' => $assign->id,
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
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Ha ocurrido un error, por favor reportar con el siguiente mensaje: '.$e->getMessage());
        }

        return view('cost.assign.edit', compact('menuId', 'assign', 'products', 'categories', 'persons'));
    }

    public function destroy(Doc $assign)
    {
        DB::beginTransaction();
        try {
            $assign->update(['code' => 'ANULADO_'.$assign->code,
                'subtotal' => 0,
                'iva' => 0,
                'total' => 0,
                'state' => 0,
                'text' => null,
            ]);
            $assign->mvtos()->update([
                'cant' => 0,
                'valueu' => 0,
                'iva' => 0,
                'valuet' => 0,
                'cant2' => 0,
                'value2' => 0,
                'iva2' => 0,
                'valuet2' => 0,
                'costu' => 0,
                'state' => 0,
                'activity_id' => null,
            ]);
            DB::commit();
            return redirect()->route('assign.index')->with('success', 'Se ha eliminado la asignación de insumos correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Ha ocurrido un error, por favor reportar con el siguiente mensaje: '.$e->getMessage());
        }
    }

    public function attachment(Doc $assign)
    {
        return view('cost.assign.attachment', compact('assign'));
    }
}
