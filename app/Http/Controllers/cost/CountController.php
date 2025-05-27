<?php

namespace App\Http\Controllers\cost;

use App\Http\Controllers\Controller;
use App\Http\Requests\cost\CountRequest;
use App\Models\Doc;
use App\Models\Mvto;
use App\Models\Order;
use App\Models\Project\Activity;
use App\Services\EventService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CountController extends Controller
{
    private $menuId = 507;
    private $menuIdsNot = [505, 506];

    public function index()
    {
        return view('cost.count.index');
    }

    
    public function create()
    {
        Order::where('menu_id', $this->menuId)
            ->where('company_id', Auth::user()->current_company_id)
            ->where('user_id', Auth::id())
            ->delete();
            
        $menuId = $this->menuId;

        return view('cost.count.create', compact('menuId'));
    }

    
    public function store(CountRequest $request, EventService $eventService)
    {
        DB::beginTransaction();
        try {
            $orders = Order::where('menu_id', $this->menuId)
                ->where('company_id', Auth::user()->current_company_id)
                ->where('user_id', Auth::id())
                ->get();

            if ($orders->isEmpty()) {
                return back()->withInput()->with('error', 'No se ha registrado ningún producto en el conteo.');
            }

            foreach ($orders as $order) {
                if (!$order->product_id) {
                    return back()->withInput()->with('error', 'Existen movimientos sin productos asignados.');
                }
                else if ($order->cant - $order->cant2 < 0 && !$order->activity_id) {
                    return back()->withInput()->with('error', 'Todos los productos con cantidad negativa deben tener una actividad asignada.');
                }
            }
            $total = $orders->sum(function ($order) {
                return ($order->cant - $order->cant2) * $order->value;
            });

            if (isset($request->doc_id)) {
                $doc = Doc::find($request->doc_id);                

                $doc->update([
                    'date' => $request->date,
                    'person_id' => $request->person_id,
                    'subtotal' => $total,
                    'total' => $total,
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
                    'activity_id' => null,
                    'space_id' => null,
                ]);
            }
            else
            {
                $doc = Doc::create([
                    'menu_id' => $this->menuId,
                    'company_id' => Auth::user()->current_company_id,
                    'code' => 'AJI',
                    'num' => Doc::where('menu_id', $this->menuId)
                        ->where('company_id', Auth::user()->current_company_id)
                        ->max('num') + 1,
                    'date' => $request->date,
                    'person_id' => $request->person_id,
                    'subtotal' => $total,
                    'iva' => 0,
                    'total' => $total,
                    'state' => 1,
                    'user_id' => Auth::id(),
                    'text' => $request->text,
                ]);
            }            

            foreach ($orders as $order) {
                $mvto = Mvto::where('doc_id', $order->doc_id)->where('product_id', $order->product_id)->where('unit_id', $order->unit_id)->where('state', 0)->first();
                $activity = Activity::find($order->activity_id);
                $spaceId = $activity ? $activity->space_id : $order->space_id;
                if ($mvto) {
                    $mvto->update([
                        'cant' => $order->cant - $order->cant2,
                        'valueu' => $order->value,
                        'iva' => $order->iva,
                        'valuet' => ($order->cant - $order->cant2) * $order->value,
                        'product2_id' => $order->product_id,
                        'unit2_id' => $order->unit_id,
                        'cant2' => $order->cant - $order->cant2,
                        'valueu2' => $order->value,
                        'valuet2' => ($order->cant - $order->cant2) * $order->value,
                        'costu' => $order->value,
                        'state' => 1,
                        'activity_id' => $order->activity_id,
                        'space_id' => $spaceId,
                    ]);

                    if ($spaceId) {
                        $eventService->create('EVNT_Cost', $spaceId, Carbon::now(), 'Actualiza costo por conteo inventario: '. $doc->num, null, $this->menuId, $doc->id, $mvto->id);
                    }
                } else {
                    $mvto = Mvto::create([
                        'doc_id' => $doc->id,
                        'product_id' => $order->product_id,
                        'unit_id' => $order->unit_id,
                        'cant' => $order->cant - $order->cant2,
                        'valueu' => $order->value,
                        'iva' => $order->iva,
                        'valuet' => ($order->cant - $order->cant2) * $order->value,
                        'product2_id' => $order->product_id,
                        'unit2_id' => $order->unit_id,
                        'cant2' => $order->cant - $order->cant2,
                        'valueu2' => $order->value,
                        'valuet2' => ($order->cant - $order->cant2) * $order->value,
                        'costu' => $order->value,
                        'state' => 1,
                        'activity_id' => $order->activity_id,
                        'space_id' => $spaceId,
                    ]);

                    if ($spaceId) {
                        $eventService->create('EVNT_Cost', $spaceId, Carbon::now(), 'Genera costo por conteo inventario: '. $doc->num, null, $this->menuId, $doc->id, $mvto->id);
                    }
                }
            }
            
            Order::where('menu_id', $this->menuId,)
                ->where('company_id', Auth::user()->current_company_id)
                ->where('user_id', Auth::id())
                ->delete();

            DB::commit();
            return redirect()->route('count.index')->with('success', 'Se ha registrado el conteo correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Ha ocurrido un error, por favor reportar con el siguiente mensaje: '.$e->getMessage());
        }
    }

    public function edit(Doc $count)
    {
        $menuId = $this->menuId;
        DB::beginTransaction();
        try 
        {
            Order::where('menu_id', $this->menuId)
                ->where('company_id', Auth::user()->current_company_id)
                ->where('user_id', Auth::id())
                ->delete();

            foreach ($count->mvtos as $order) {
                if ($order->state == 1 && $order->cant2 <> 0) 
                {
                    $balance = $this->getBalanceProduct($order->product_id, $order->unit_id, $count->date, $count->id);
                    Order::create([
                        'doc_id' => $count->id,
                        'menu_id' => $this->menuId,
                        'company_id' => Auth::user()->current_company_id,
                        'product_id' => $order->product_id,
                        'unit_id' => $order->unit_id,
                        'cant' => $order->cant + $balance,
                        'value' => $order->valueu,
                        'cant2' => $balance,
                        'iva' => $order->iva,
                        'activity_id' => $order->activity_id,
                        'user_id' => Auth::id(),
                    ]);
                }
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Ha ocurrido un error, por favor reportar con el siguiente mensaje: '.$e->getMessage());
        }

        return view('cost.count.edit', compact('count', 'menuId'));
    }

    private function getBalanceProduct($productId, $unitId, $date, $docId)
    {
        return Mvto::where('product_id', $productId)
            ->where('unit_id', $unitId)
            ->where('cant2', '<>', 0)
            ->where('state', 1)
            ->whereHas('doc', function ($query) use ($date, $docId) {
                $query->where('state', 1)
                ->whereNotIn('menu_id', $this->menuIdsNot)
                ->where('date', '<=', $date ?? Carbon::now())
                ->where('company_id', Auth::user()->current_company_id)
                ->where('id', '<>', $docId);
            })
            ->sum('cant2');
    }
    

    public function destroy(Doc $count)
    {
        DB::beginTransaction();
        try {
            $count->update(['code' => 'ANULADO_'.$count->code,
                'subtotal' => 0,
                'iva' => 0,
                'total' => 0,
                'state' => 0,
                'text' => null,
            ]);
            $count->mvtos()->update([
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
            return redirect()->route('count.index')->with('success', 'Se ha eliminado el conteo correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Ha ocurrido un error, por favor reportar con el siguiente mensaje: '.$e->getMessage());
        }
    }

    public function attachment(Doc $count)
    {
        return view('cost.count.attachment', compact('count'));
    }
}
