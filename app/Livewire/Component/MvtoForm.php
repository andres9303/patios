<?php

namespace App\Livewire\Component;

use App\Models\Master\Person;
use App\Models\Master\Product;
use App\Models\Mvto;
use App\Models\Order;
use App\Models\Project\Activity;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class MvtoForm extends Component
{
    public $menuId;
    public $route;
    public $doc;
    public $isActivities;
    public $calculateValue;

    public $categories;
    public $selectedCategory = 'all';
    public $products_base = [];
    public $products = [];
    public $orders = [];
    public $persons = [];
    public $activities = [];
    public $person_id;
    public $date;
    public $code;
    public $num;
    public $person;
    public $subtotal = 0;
    public $iva = 0;
    public $total = 0;

    protected $rules = [
        'person_id' => 'required|exists:people,id',
        'date' => 'required|date',
        'code' => 'nullable|string|max:10',
        'num' => 'required|string|max:50',
    ];

    //OJO: INTEGRIDAD DEL DOCUMENTO EDITABLE: agregar el idDoc como hide al formulario y validarlo en el controlador

    public function mount($menuId, $route, $products, $categories, $isActivities = false, $calculateValue = false, $doc = null)
    {
        $this->menuId = $menuId;
        $this->route = $route;
        $this->isActivities = $isActivities;
        $this->calculateValue = $calculateValue;
        $this->doc = $doc;
        if ($this->doc) {
            $this->person_id = $this->doc->person_id;
            $this->date = $this->doc->date;
            $this->code = $this->doc->code;
            $this->num = $this->doc->num;
            $this->person = $this->doc->person;
        }
        else {
            $orders = Order::where('menu_id', $this->menuId)
                ->where('company_id', Auth::user()->current_company_id)
                ->where('user_id', Auth::id())
                ->whereNotNull('doc_id')
                ->get();

            if ($orders->count() > 0) {
                $this->person_id = $orders->first()->doc->person_id;
                $this->date = $orders->first()->doc->date;
                $this->code = $orders->first()->doc->code;
                $this->num = $orders->first()->doc->num;
                $this->person = $orders->first()->doc->person;
            }
        }

        $this->products_base = $products;
        $this->categories = $categories;
        $this->persons = Person::where('isSupplier', 1)->where('state', 1)->get();
        if ($this->isActivities)
            $this->loadActivities();
        
        $this->loadProducts();
        $this->loadOrders();
    }

    public function loadProducts()
    {
        if ($this->selectedCategory === 'all') {
            $this->products = $this->products_base;
        } else {
            $this->products = $this->products_base->filter(function ($product) {
                return $product->item_id == $this->selectedCategory;
            });
        }
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
                $query->where('state', 1)
                    ->where('type', 0);
            })->get();
    }

    public function calculateTotals()
    {
        $this->subtotal = $this->orders->sum(function ($order) {
            return $order->cant * $order->value;
        });
        $this->iva = $this->orders->sum(function ($order) {
            return $order->cant * $order->value * $order->iva / 100;
        });
        $this->total = $this->subtotal + $this->iva;
    }

    public function addProduct($productId)
    {
        Order::create([
            'menu_id' => $this->menuId,
            'company_id' => Auth::user()->current_company_id,
            'product_id' => $productId,
            'unit_id' => Product::find($productId)->unit_id,
            'cant' => 1,
            'value' => $this->calculateValue ? $this->getValueProduct($productId, Product::find($productId)->unit_id) : 0,
            'iva' => 0,
            'user_id' => Auth::id(),
        ]);
        $this->loadOrders();
    }

    public function getValueProduct($productId, $unitId)
    {
        return Mvto::where('product_id', $productId)
            ->where('unit_id', $unitId)
            ->whereHas('doc', function ($query) {
                $query->where('state', 1)
                ->where('menu_id', 502)
                ->where('company_id', Auth::user()->current_company_id);
            })
            ->orderBy('id', 'desc')
            ->first()->valueu ?? 0;
    }

    public function updateOrder($orderId, $field, $value)
    {
        $order = Order::find($orderId);
        $order->{$field} = $value;
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
        return view('livewire.component.mvto-form');
    }
}
