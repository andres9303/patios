<?php

namespace App\Http\Controllers\cost;

use App\Http\Controllers\Controller;
use App\Http\Requests\cost\AdjustmentRequest;
use App\Models\Doc;
use App\Models\Mvto;
use App\Models\Order;
use App\Models\Project\Activity;
use App\Services\EventService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdjustmentController extends Controller
{
    private $menuId = 504;

    public function index()
    {
        return view('cost.adjustment.index');
    }

    
    public function create()
    {
        $menuId = $this->menuId;

        Order::where('menu_id', $this->menuId)
            ->where('company_id', Auth::user()->current_company_id)
            ->where('user_id', Auth::id())
            ->delete();

        return view('cost.adjustment.create', compact('menuId'));
    }

    
    public function store(AdjustmentRequest $request, EventService $eventService)
    {
        DB::beginTransaction();
        try {
            $orders = Order::where('menu_id', $this->menuId)
                ->where('company_id', Auth::user()->current_company_id)
                ->where('user_id', Auth::id())
                ->get();

            if ($orders->isEmpty()) {
                return back()->withInput()->with('error', 'No se ha registrado ningún producto en la factura.');
            }

            foreach ($orders as $order) {
                if (!$order->product_id) {
                    return back()->withInput()->with('error', 'Existen movimientos sin productos asignados.');
                }
                else if ($order->cant < 0 && !$order->activity_id) {
                    return back()->withInput()->with('error', 'Todos los productos con cantidad negativa deben tener una actividad asignada.');
                }
            }
            $total = $orders->sum(function ($order) {
                return $order->cant * $order->value;
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
                        'cant' => $order->cant,
                        'valueu' => $order->value,
                        'iva' => $order->iva,
                        'valuet' => $order->cant * $order->value,
                        'product2_id' => $order->product_id,
                        'unit2_id' => $order->unit_id,
                        'cant2' => $order->cant,
                        'valueu2' => $order->value,
                        'valuet2' => $order->cant * $order->value,
                        'costu' => $order->value,
                        'state' => 1,
                        'activity_id' => $order->activity_id,
                        'space_id' => $spaceId,
                    ]);

                    if ($spaceId) {
                        $eventService->create('EVNT_Cost', $spaceId, Carbon::now(), 'Actualiza costo por ajuste: '. $doc->num, null, $this->menuId, $doc->id, $mvto->id);
                    }
                } else {
                    $mvto = Mvto::create([
                        'doc_id' => $doc->id,
                        'product_id' => $order->product_id,
                        'unit_id' => $order->unit_id,
                        'cant' => $order->cant,
                        'valueu' => $order->value,
                        'iva' => $order->iva,
                        'valuet' => $order->cant * $order->value,
                        'product2_id' => $order->product_id,
                        'unit2_id' => $order->unit_id,
                        'cant2' => $order->cant,
                        'valueu2' => $order->value,
                        'valuet2' => $order->cant * $order->value,
                        'costu' => $order->value,
                        'state' => 1,
                        'activity_id' => $order->activity_id,
                        'space_id' => $spaceId,
                    ]);

                    if ($spaceId) {
                        $eventService->create('EVNT_Cost', $spaceId, Carbon::now(), 'Genera costo por ajuste: '. $doc->num, null, $this->menuId, $doc->id, $mvto->id);
                    }
                }
            }
            
            Order::where('menu_id', $this->menuId,)
                ->where('company_id', Auth::user()->current_company_id)
                ->where('user_id', Auth::id())
                ->delete();

            DB::commit();
            return redirect()->route('adjustment.index')->with('success', 'Se ha registrado el ajuste correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Ha ocurrido un error, por favor reportar con el siguiente mensaje: '.$e->getMessage());
        }
    }

    public function edit(Doc $adjustment)
    {
        $menuId = $this->menuId;
        DB::beginTransaction();
        try 
        {
            Order::where('menu_id', $this->menuId)
                ->where('company_id', Auth::user()->current_company_id)
                ->where('user_id', Auth::id())
                ->delete();

            foreach ($adjustment->mvtos as $order) {
                if ($order->state == 1 && $order->cant2 <> 0) 
                {
                    Order::create([
                        'doc_id' => $adjustment->id,
                        'menu_id' => $this->menuId,
                        'company_id' => Auth::user()->current_company_id,
                        'product_id' => $order->product_id,
                        'unit_id' => $order->unit_id,
                        'cant' => $order->cant,
                        'value' => $order->valueu,
                        'cant2' => Mvto::where('product_id', $order->product_id)
                            ->where('unit_id', $order->unit_id)
                            ->where('cant2', '<>', 0)
                            ->where('state', 1)
                            ->whereHas('doc', function (Builder $query) use ($adjustment) {
                                $query->where('state', 1)
                                ->where('date', '<=', $adjustment->date ?? Carbon::now())
                                ->where('company_id', Auth::user()->current_company_id);
                            })
                            ->sum('cant2'),
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

        return view('cost.adjustment.edit', compact('adjustment', 'menuId'));
    }

    public function destroy(Doc $adjustment)
    {
        DB::beginTransaction();
        try {
            $adjustment->update(['code' => 'ANULADO_'.$adjustment->code,
                'subtotal' => 0,
                'iva' => 0,
                'total' => 0,
                'state' => 0,
                'text' => null,
            ]);
            $adjustment->mvtos()->update([
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
            return redirect()->route('adjustment.index')->with('success', 'Se ha eliminado la factura correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Ha ocurrido un error, por favor reportar con el siguiente mensaje: '.$e->getMessage());
        }
    }

    public function attachment(Doc $adjustment)
    {
        return view('cost.adjustment.attachment', compact('adjustment'));
    }
}
