<?php

namespace App\Livewire\Component;

use App\Models\Master\Company;
use Livewire\Component;

class CompanySelector extends Component
{
    public $selectedCompanies = [];
    public $companies;

    public function mount($selected = []) {
        $this->selectedCompanies = $selected;
        $this->companies = Company::where('state', 1)->get();
    }
    
    public function render()
    {
        return view('livewire.component.company-selector');
    }
}
