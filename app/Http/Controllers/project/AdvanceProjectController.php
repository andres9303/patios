<?php

namespace App\Http\Controllers\project;

use App\Http\Controllers\Controller;
use App\Http\Requests\project\AdvanceProjectRequest;
use App\Models\Doc;
use App\Models\Mvto;
use App\Models\Order;
use App\Models\Project\Project;
use App\Services\EventService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdvanceProjectController extends Controller
{
    private $menuId = 402;

    public function index()
    {
        return view('project.advance.index');
    }

    public function create()
    {
        $menuId = $this->menuId;
        return view('project.advance.create', compact('menuId'));
    }

    public function store(AdvanceProjectRequest $request, EventService $eventService)
    {
        DB::beginTransaction();
        try {
            $orders = Order::where('menu_id', $this->menuId)
                ->where('company_id', Auth::user()->current_company_id)
                ->where('user_id', Auth::id())
                ->get();

            if ($orders->isEmpty()) {
                return back()->withInput()->with('error', 'No se ha registrado ningún producto en el avance.');
            }

            $project = Project::find($request->project_id);
            $spaceId = $orders->first()->activity->space_id ?? ($project->space_id ?? null);
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
                            $eventService->create('EVNT_Ingr', $spaceId, Carbon::now(), 'Modifica Avance Proyecto: '. $request->name, null, $this->menuId, $doc->id, $mvto->id);
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
                            $eventService->create('EVNT_Ingr', $spaceId, Carbon::now(), 'Ingresa Avance Proyecto: '. $doc->num, null, $this->menuId, $doc->id, $mvto->id);
                        }
                    }                    
                }
            }

            Order::where('menu_id', $this->menuId)
                ->where('company_id', Auth::user()->current_company_id)
                ->where('user_id', Auth::id())
                ->delete();

            DB::commit();
            return redirect()->route('advance-project.index')->with('success', 'Se ha registrado el avance correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function edit(Doc $advance_project)
    {
        $menuId = $this->menuId;
        Order::where('menu_id', $this->menuId)
                ->where('company_id', Auth::user()->current_company_id)
                ->where('user_id', Auth::id())
                ->delete();

        foreach ($advance_project->mvtos as $order) {
            if ($order->state == 1) 
            {
                Order::create([
                    'doc_id' => $advance_project->id,
                    'menu_id' => $this->menuId,
                    'company_id' => $advance_project->company_id,
                    'product_id' => $order->product_id,
                    'unit_id' => $order->unit_id,
                    'cant' => $order->cant,
                    'value' => $order->valueu,
                    'cant2' => Mvto::where('activity_id', $order->activity_id)
                                    ->where('state', 1)
                                    ->where('doc_id', '<>', $advance_project->id)
                                    ->whereHas('doc', function ($query) use ($advance_project) {
                                        $query->where('state', 1)
                                            ->where('menu_id', $this->menuId)
                                            ->where('company_id', $advance_project->company_id);
                                    })
                                    ->sum('cant') ?? 0,
                    'activity_id' => $order->activity_id,
                    'space_id' => $order->space_id,
                    'user_id' => Auth::id(),
                ]);
            }
        }

        return view('project.advance.edit', compact('advance_project', 'menuId'));
    }

    public function destroy(Doc $advance_project)
    {
        DB::beginTransaction();
        try {
            $advance_project->update([
                'state' => 0,
            ]);
            Mvto::where('doc_id', $advance_project->id)->update([
                'cant' => 0,
                'valueu' => 0,
                'iva' => 0,
                'valuet' => 0,
                'state' => 0,
            ]);

            DB::commit();
            return redirect()->route('advance-project.index')->with('success', 'Se ha eliminado el avance correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    public function attachment(Doc $advance_project)
    {
        return view('project.advance.attachment', compact('advance_project'));
    }
}
