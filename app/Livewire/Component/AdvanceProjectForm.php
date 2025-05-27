<?php

namespace App\Livewire\Component;

use App\Models\Master\Person;
use App\Models\Master\Product;
use App\Models\Master\Unit;
use App\Models\Mvto;
use App\Models\Order;
use App\Models\Project\Activity;
use App\Models\Project\Project;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class AdvanceProjectForm extends Component
{
    public $menuId;
    public $doc;
    public $docId;

    public $labelPerson;    
    public $isAdvance;
    public $persons = [];
    public $projects = [];
    public $orders = [];
    public $activities = [];

    public $date;
    public $person_id;
    public $project_id;
    public $text;

    public function mount($menuId, $doc = null, $labelPerson = 'Responsable', $isAdvance = true)
    {
        $this->menuId = $menuId;
        $this->doc = $doc;

        $this->labelPerson = $labelPerson;
        $this->isAdvance = $isAdvance;
        $this->loadPersons();
        $this->loadProjects();
        if ($this->doc) {
            $this->docId = $this->doc->id;
            $this->person_id = $this->doc->person_id;
            $this->date = $this->doc->date;
            $this->person_id = $doc->person_id;
            $this->project_id = $doc->ref;
            $this->text = $this->doc->text;
        }
        else {
            $orders = Order::where('menu_id', $this->menuId)
                ->where('company_id', Auth::user()->current_company_id)
                ->where('user_id', Auth::id())
                ->whereNotNull('doc_id')
                ->get();

            if ($orders->count() > 0) {
                $this->doc = $orders->first()->doc;
                $this->docId = $this->doc->id;
                $this->person_id = $this->doc->person_id;
                $this->date = $this->doc->date;
                $this->text = $this->doc->text;
                $this->project_id = $this->doc->ref;
            }
            else
            {
                $this->loadActivities(false);
            }
        }

        $this->loadOrders();
    }

    public function updateOrder($orderId, $field, $value)
    {
        $order = Order::findOrFail($orderId);
        $order->$field = $value ?? 0;
        $order->save();
    }

    public function loadActivities($reload = true)
    {
        if ($reload) 
        {
            $this->deleteOrders();
        }
            
        $this->getActivities();
        $this->loadOrders();
    }    
    
    public function loadOrders()
    {
        $this->orders = Order::where('menu_id', $this->menuId)
            ->where('company_id', Auth::user()->current_company_id)
            ->where('user_id', Auth::id())
            ->get();

        if (count($this->orders) > 0 && $this->project_id == null) {
            $this->project_id = $this->orders[0]->activity->project->id;
        }
    }
    public function loadPersons()
    {
        $this->persons = Person::where('isEmployee', 1)->where('state', 1)->orderBy('name')->get();
    }

    public function loadProjects()
    {
        if ($this->isAdvance) {
            $this->projects = Project::where('state', 1)->orderBy('name')->get();
        }
        else {
            $this->projects = Project::where('state', 1)->whereNotNull('space_id')->orderBy('name')->get();
        }
    }
    
    public function render()
    {
        return view('livewire.component.advance-project-form');
    }

    private function deleteOrders()
    {
        Order::where('menu_id', $this->menuId)
            ->where('company_id', Auth::user()->current_company_id)
            ->where('user_id', Auth::id())
            ->delete();
    }

    private function getBalanceActivity($activityId)
    {
        return Mvto::where('activity_id', $activityId)
            ->where('state', 1)
            ->where('doc_id', '<>', $this->docId)
            ->whereHas('doc', function ($query) {
                $query->where('state', 1)
                    ->where('menu_id', $this->menuId)
                    ->where('company_id', Auth::user()->current_company_id);
            })
            ->sum('cant') ?? 0;
    }

    private function getProductAdvance()
    {
        return Product::where('name', 'Avance de Actividad')->first() ?? Product::create([
            'code' => 'APY',
            'name' => 'Avance de Actividad',
            'unit_id' => Unit::first()->id,
            'state' => 1,
        ]);
    }

    private function getActivities()
    {
        $product = $this->getProductAdvance();

        $this->activities = Activity::where('project_id', $this->project_id)->where('state', 1)->get();
        foreach ($this->activities as $activity) {
            Order::create([
                'menu_id' => $this->menuId,
                'doc_id' => $this->docId,
                'company_id' => Auth::user()->current_company_id,
                'user_id' => Auth::id(),
                'product_id' => $product->id,
                'unit_id' => $product->unit_id,
                'activity_id' => $activity->id,
                'cant' => 0,
                'cant2' => $this->getBalanceActivity($activity->id),
                'space_id' => $activity->project->space_id,
            ]);
        }
    }
}
