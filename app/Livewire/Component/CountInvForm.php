<?php

namespace App\Livewire\Component;

use App\Models\Config\Item;
use App\Models\Master\Person;
use App\Models\Master\Product;
use App\Models\Master\Space;
use App\Models\Mvto;
use App\Models\Order;
use App\Models\Project\Activity;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class CountInvForm extends Component
{    
    private $menuIdsNot = [505, 506];
    private $menuIdValue = 502;
    private $catalog_id = 203;

    public $menuId;
    public $docId;
    public $route;
    public $doc;
    public $calculateValue;
    public $searchTerm = '';
    
    public $labelPerson;
    public $labelDate;

    public $categories;
    public $selectedCategory = 'all';
    public $selectedClass = '0';
    public $products_base = [];
    public $products = [];
    public $orders = [];
    public $persons = [];
    public $activities = [];
    public $spaces = [];
    public $person_id;
    public $date;
    public $person;
    public $text;
    public $total = 0;

    protected $rules = [
        'person_id' => 'required|exists:people,id',
        'date' => 'required|date',
    ];

    public function mount($menuId, $route, $doc = null, $labelPerson = 'Responsable', $labelDate = 'Fecha factura')
    {
        $this->menuId = $menuId;
        $this->route = $route;
        $this->doc = $doc;
        if ($this->doc) {
            $this->docId = $this->doc->id;
            $this->person_id = $this->doc->person_id;
            $this->date = $this->doc->date;
            $this->person = $this->doc->person;
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
                $this->person = $this->doc->person;
                $this->text = $this->doc->text;
            }
        }

        $this->labelPerson = $labelPerson;
        $this->labelDate = $labelDate;
        $this->loadSpaces();

        $this->products_base = Product::where('state', 1)->where('isinventory', 1)->whereHas('companies', function ($query) {
            $query->where('company_id', Auth::user()->current_company_id);
        })->orderBy('name')->get();
        $this->categories = Item::where('catalog_id', $this->catalog_id)->orderBy('name')->get();
        $this->persons = Person::where('isEmployee', 1)->where('state', 1)->orderBy('name')->get();
        $this->loadActivities();
        
        $this->loadProducts();
        $this->loadOrders();
    }

    public function loadProducts()
    {
        // Filtro por categoría
        $filtered = $this->selectedCategory === 'all'
            ? $this->products_base
            : $this->products_base->filter(function ($product) {
                return $product->item_id == $this->selectedCategory;
            });

        // Filtro por clase de producto (rotación)
        if ($this->selectedClass !== 'all') {
            $filtered = $filtered->filter(function ($product) {
                return $product->class == $this->selectedClass;
            });
        }

        // Filtro por término de búsqueda
        if (!empty($this->searchTerm)) {
            $searchTerm = strtolower($this->searchTerm);
            $filtered = $filtered->filter(function ($product) use ($searchTerm) {
                return str_contains(strtolower($product->name), $searchTerm) ||
                       str_contains(strtolower($product->code), $searchTerm);
            });
        }
        
        $this->products = $filtered;
    }

    public function loadOrders()
    {
        $this->orders = Order::where('menu_id', $this->menuId)
            ->where('company_id', Auth::user()->current_company_id)
            ->where('user_id', Auth::id())
            ->get();
        $this->calculateTotals();
    }

    public function loadActivities()
    {
        $this->activities = Activity::where('state', 1)            
            ->whereHas('project', function ($query) {
                $query->where('state', 1);
            })->get()->sortBy(function($activity) {
                return $activity->project->type; // Ordenar la colección final
            });
    }

    public function loadSpaces()
    {
        $this->spaces = Space::where('state', 1)->where('company_id', Auth::user()->current_company_id)->orderBy('name')->get();
    }

    public function calculateTotals()
    {
        $this->total = $this->orders->sum(function ($order) {
            return $order->cant * $order->value;
        });
    }

    public function addProduct($productId)
    {
        if (Order::where('menu_id', $this->menuId)
            ->where('company_id', Auth::user()->current_company_id)
            ->where('user_id', Auth::id())
            ->where('product_id', $productId)
            ->exists()) {
            return;
        }

        $unitId = Product::find($productId)->unit_id;
        Order::create([
            'menu_id' => $this->menuId,
            'company_id' => Auth::user()->current_company_id,
            'product_id' => $productId,
            'unit_id' => Product::find($productId)->unit_id,
            'cant' => 0,
            'value' => $this->getValueProduct($productId, $unitId),
            'cant2' => $this->getBalanceProduct($productId, $unitId),
            'iva' => 0,
            'user_id' => Auth::id(),
        ]);
        $this->loadOrders();
    }

    private function getBalanceProduct($productId, $unitId)
    {
        return Mvto::where('product_id', $productId)
            ->where('unit_id', $unitId)
            ->where('cant2', '<>', 0)
            ->where('state', 1)
            ->whereHas('doc', function ($query) {
                $query->where('state', 1)
                ->where('date', '<=', $this->date ?? Carbon::now())
                ->whereNotIn('menu_id', $this->menuIdsNot)
                ->where('company_id', Auth::user()->current_company_id);
            })
            ->sum('cant2');
    }

    private function getValueProduct($productId, $unitId)
    {
        return Mvto::where('product_id', $productId)
            ->where('unit_id', $unitId)
            ->whereHas('doc', function ($query) {
                $query->where('state', 1)
                ->where('menu_id', $this->menuIdValue)
                ->where('company_id', Auth::user()->current_company_id);
            })
            ->orderBy('id', 'desc')
            ->first()->valueu ?? 0;
    }

    public function updateDate($value)
    {
        $orders = Order::where('menu_id', $this->menuId)
            ->where('company_id', Auth::user()->current_company_id)
            ->where('user_id', Auth::id())
            ->get();
        foreach ($orders as $order) {
            $order->update([
                'cant2' => $this->getBalanceProduct($order->product_id, $order->unit_id),
            ]);
        }
        
        $this->loadOrders();
    }

    public function updateOrder($orderId, $field, $value)
    {
        $order = Order::find($orderId);
        $order->{$field} = $value ?? 0;
        $order->save();
        $this->loadOrders();
    }

    public function removeOrder($orderId)
    {
        Order::destroy($orderId);
        $this->loadOrders();
    }
    
    public function render()
    {
        return view('livewire.component.count-inv-form');
    }
}
