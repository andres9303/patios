<?php

namespace App\Http\Controllers\space;

use App\Http\Controllers\Controller;
use App\Http\Requests\Space\InputRequest;
use App\Models\Doc;
use App\Models\Mvto;
use App\Models\Order;
use App\Models\Project\Project;
use App\Services\EventService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InputController extends Controller
{
    private $menuId = 602;

    public function index()
    {
        return view('space.input.index');
    }

    public function create()
    {
        $menuId = $this->menuId;
        return view('space.input.create', compact('menuId'));
    }

    public function store(InputRequest $request, EventService $eventService)
    {
        DB::beginTransaction();
        try {
            $orders = Order::where('menu_id', $this->menuId)
                ->where('company_id', Auth::user()->current_company_id)
                ->where('user_id', Auth::id())
                ->get();

            if ($orders->isEmpty()) {
                return back()->withInput()->with('error', 'No se ha registrado ningún movimiento.');
            }

            $project = Project::find($request->project_id);
            $spaceId = $orders->first()->activity->space_id ?? $project->space_id;
            if (isset($request->doc_id)) {
                $doc = Doc::find($orders->first()->doc_id);

                $doc->update([
                    'date' => $request->date,
                    'person_id' => $request->person_id,
                    'state' => 1,
                    'user_id' => Auth::id(),
                    'ref' => $request->project_id,
                    'text' => $request->text,
                    'space_id' => $spaceId,
                ]);

                Mvto::where('doc_id', $doc->id)->update([
                    'cant' => 0,
                    'valueu' => 0,
                    'iva' => 0,
                    'valuet' => 0,
                    'state' => 0,
                ]);
            }
            else
            {
                $doc = Doc::create([
                    'menu_id' => $this->menuId,
                    'company_id' => Auth::user()->current_company_id,
                    'code' => 'APY',
                    'num' => Doc::where('menu_id', $this->menuId)
                        ->where('company_id', Auth::user()->current_company_id)
                        ->max('num') + 1,
                    'date' => $request->date,
                    'person_id' => $request->person_id,
                    'ref' => $request->project_id,
                    'state' => 1,
                    'user_id' => Auth::id(),
                    'text' => $request->text,
                    'space_id' => $spaceId,
                ]);
            }

            foreach ($orders as $order) {
                if ($order->cant > 0) {
                    $mvto = Mvto::where('doc_id', $order->doc_id)->where('product_id', $order->product_id)->where('activity_id', $order->activity_id)->first();
                    if ($mvto) {
                        $mvto->update([
                            'cant' => $order->cant,
                            'valueu' => $order->value,
                            'valuet' => $order->cant * $order->value,
                            'state' => 1,
                            'space_id' => $spaceId,
                        ]);

                        if ($spaceId) {
                            $eventService->create('EVNT_Ingr', $spaceId, Carbon::now(), 'Modifica Ingreso: '. $request->name, null, $this->menuId, $doc->id, $mvto->id);
                        }
                    } else {
                        $mvto = Mvto::create([
                            'doc_id' => $doc->id,
                            'product_id' => $order->product_id,
                            'unit_id' => $order->unit_id,
                            'cant' => $order->cant,
                            'valueu' => $order->value,
                            'valuet' => $order->cant * $order->value,
                            'state' => 1,
                            'activity_id' => $order->activity_id,
                            'space_id' => $spaceId,
                        ]);

                        if ($spaceId) {
                            $eventService->create('EVNT_Ingr', $spaceId, Carbon::now(), 'Registra Ingreso: '. $doc->num, null, $this->menuId, $doc->id, $mvto->id);
                        }
                    }                    
                }
            }

            Order::where('menu_id', $this->menuId)
                ->where('company_id', Auth::user()->current_company_id)
                ->where('user_id', Auth::id())
                ->delete();

            DB::commit();
            return redirect()->route('input.index')->with('success', 'Se ha registrado el avance correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function edit(Doc $input)
    {
        $menuId = $this->menuId;
        Order::where('menu_id', $this->menuId)
                ->where('company_id', Auth::user()->current_company_id)
                ->where('user_id', Auth::id())
                ->delete();

        foreach ($input->mvtos as $order) {
            if ($order->state == 1) 
            {
                Order::create([
                    'doc_id' => $input->id,
                    'menu_id' => $this->menuId,
                    'company_id' => $input->company_id,
                    'product_id' => $order->product_id,
                    'unit_id' => $order->unit_id,
                    'cant' => $order->cant,
                    'value' => $order->valueu,
                    'cant2' => Mvto::where('activity_id', $order->activity_id)
                                    ->where('state', 1)
                                    ->where('doc_id', '<>', $input->id)
                                    ->whereHas('doc', function ($query) use ($input) {
                                        $query->where('state', 1)
                                            ->where('menu_id', $this->menuId)
                                            ->where('company_id', $input->company_id);
                                    })
                                    ->sum('cant') ?? 0,
                    'activity_id' => $order->activity_id,
                    'space_id' => $order->space_id,
                    'user_id' => Auth::id(),
                ]);
            }
        }

        return view('space.input.edit', compact('input', 'menuId'));
    }

    public function destroy(Doc $input)
    {
        DB::beginTransaction();
        try {
            $input->update([
                'state' => 0,
            ]);
            Mvto::where('doc_id', $input->id)->update([
                'cant' => 0,
                'valueu' => 0,
                'iva' => 0,
                'valuet' => 0,
                'state' => 0,
            ]);

            DB::commit();
            return redirect()->route('input.index')->with('success', 'Se ha eliminado el avance correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }
}
