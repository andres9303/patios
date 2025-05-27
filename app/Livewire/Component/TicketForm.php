<?php

namespace App\Livewire\Component;

use App\Models\Config\Item;
use App\Models\Master\Category;
use App\Models\Master\Location;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class TicketForm extends Component
{

    public $ticket;
    public $isManage = false;
    public $priorities;
    public $locations;
    public $categories;
    public $subcategories;
    public $users;
    
    public $categoryId;

    public $debugMessage = '';

    public function mount($ticket = null, $isManage = false)
    {
        $this->ticket = $ticket;
        $this->isManage = $isManage;
        $this->categoryId = $ticket->category_id ?? null;

        $this->locations = Location::where('state', 1)->where(function ($query) {
            $query->where('company_id', Auth::user()->current_company_id)
                  ->orWhere('company_id', 1);
        })->orderBy('text')->get() ?? collect();

        $this->categories = Category::where('state', 1)->where(function ($query) {
            $query->where('company_id', Auth::user()->current_company_id)
                  ->orWhere('company_id', 1);
        })->whereNull('ref_id')->orderBy('text')->get() ?? collect();

        $this->priorities = Item::where('catalog_id', 3)->orderBy('order')->get() ?? collect();

        $this->users = User::whereHas('companies', function ($query) {
            $query->whereIn('company_user.company_id', [1, Auth::user()->current_company_id]);
        })->get() ?? collect();

        $this->loadSubcategories();
    }

    public function handleCategoryChange($value)
    {
        $this->categoryId   = $value;
        $this->loadSubcategories();
    }

    private function loadSubcategories()
    {
        $this->subcategories = Category::where('ref_id', $this->categoryId)
            ->where('state', 1)
            ->where(function ($query) {
                $query->where('company_id', Auth::user()->current_company_id)
                      ->orWhere('company_id', 1);
            })
            ->whereNotNull('ref_id')
            ->orderBy('text')
            ->get() ?? collect();
    }

    public function render()
    {
        return view('livewire.component.ticket-form');
    }
}
