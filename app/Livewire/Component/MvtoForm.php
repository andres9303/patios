<?php

namespace App\Livewire\Component;

use App\Models\Master\Product;
use App\Models\Master\Space;
use App\Models\Mvto;
use App\Models\Order;
use App\Models\Project\Activity;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class MvtoForm extends Component
{
    public $menuId;
    public $docId;
    public $route;
    public $doc;
    public $isActivities;
    public $calculateValue;
    public $searchTerm = '';

    public $labelCode;
    public $labelDocument;
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
    public $code;
    public $num;
    public $person;
    public $text;
    public $subtotal = 0;
    public $iva = 0;
    public $total = 0;

    protected $rules = [
        'person_id' => 'required|exists:people,id',
        'date' => 'required|date',
        'code' => 'nullable|string|max:10',
        'num' => 'required|string|max:50',
    ];

    public function mount($menuId, $route, $products, $categories, $persons, $isActivities = false, $calculateValue = false, $doc = null, $labelCode = 'Código Factura', $labelDocument = 'Número Factura', $labelPerson = 'Proveedor', $labelDate = 'Fecha factura')
    {
        $this->menuId = $menuId;
        $this->route = $route;
        $this->isActivities = $isActivities;
        $this->calculateValue = $calculateValue;
        $this->doc = $doc;
        if ($this->doc) {
            $this->docId = $this->doc->id;
            $this->person_id = $this->doc->person_id;
            $this->date = $this->doc->date;
            $this->code = $this->doc->code;
            $this->num = $this->doc->num;
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
                $this->code = $this->doc->code;
                $this->num = $this->doc->num;
                $this->person = $this->doc->person;
                $this->text = $this->doc->text;
            }
        }

        $this->labelCode = $labelCode;
        $this->labelDocument = $labelDocument;
        $this->labelPerson = $labelPerson;
        $this->labelDate = $labelDate;

        $this->products_base = $products;
        $this->categories = $categories;
        $this->persons = $persons;
        if ($this->isActivities)
            $this->loadActivities();
        
        $this->loadProducts();
        $this->loadOrders();
        $this->loadSpaces();
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
        return view('livewire.component.mvto-form');
    }
}
