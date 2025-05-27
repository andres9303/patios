<?php

namespace App\Livewire\Grap;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class TicketCategoryGrap extends Component
{

    public $chartData;
    public $labels = [];
    public $datasets = [];
    public $colors = [
        'rgba(255, 99, 132, 0.6)',
        'rgba(54, 162, 235, 0.6)',
        'rgba(255, 206, 86, 0.6)',
        'rgba(75, 192, 192, 0.6)',
        'rgba(153, 102, 255, 0.6)',
        'rgba(255, 159, 64, 0.6)',
        'rgba(199, 199, 199, 0.6)',
        'rgba(83, 102, 255, 0.6)',
        'rgba(40, 159, 64, 0.6)',
        'rgba(210, 199, 199, 0.6)',
    ];

    public function mount()
    {
        $this->prepareChartData();
    }

    public function prepareChartData()
    {
        // Obtener fecha de hace 3 meses
        $threeMonthsAgo = Carbon::now()->subMonths(3);
        
        // Consultar tickets agrupados por categoría en los últimos 3 meses
        $ticketsByCategory = DB::table('tickets')
            ->join('categories', 'tickets.category2_id', '=', 'categories.id')
            ->select('categories.name as category', DB::raw('count(*) as total'))
            ->where('tickets.date', '>=', $threeMonthsAgo)
            ->where('tickets.company_id', Auth::user()->current_company_id)
            ->groupBy('categories.name')
            ->orderBy('total', 'desc')
            ->limit(10)
            ->get();
        
        // Preparar datos para el gráfico
        $this->labels = $ticketsByCategory->pluck('category')->toArray();
        $values = $ticketsByCategory->pluck('total')->toArray();
        
        // Crear dataset para Chart.js
        $this->datasets = [
            [
                'label' => 'Tickets por Categoría',
                'data' => $values,
                'backgroundColor' => array_slice($this->colors, 0, count($values)),
                'borderColor' => 'rgba(54, 162, 235, 1)',
                'borderWidth' => 1,
            ]
        ];
        
        // Formatear datos para Chart.js
        $this->chartData = [
            'labels' => $this->labels,
            'datasets' => $this->datasets,
        ];
    }
    
    public function render()
    {
        return view('livewire.grap.ticket-category-grap');
    }
}
