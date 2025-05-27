<?php

namespace App\Livewire\Grap;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class TicketPriorityGrap extends Component
{
    public $chartData;
    public $labels = [];
    public $resolvedData = [];
    public $pendingData = [];
    public $colors = [
        'resolved' => 'rgba(75, 192, 192, 0.6)',
        'pending' => 'rgba(255, 99, 132, 0.6)',
    ];

    public function mount()
    {
        $this->prepareChartData();
    }

    public function prepareChartData()
    {
        // Obtener fecha de hace 6 meses
        $sixMonthsAgo = Carbon::now()->subMonths(6);
        
        // Consultar tickets por prioridad y estado
        $ticketsByPriority = DB::table('tickets')
            ->join('items', 'tickets.item_id', '=', 'items.id')
            ->select(
                'items.name as priority',
                DB::raw('SUM(CASE WHEN tickets.state = 1 THEN 1 ELSE 0 END) as resolved'),
                DB::raw('SUM(CASE WHEN tickets.state != 1 THEN 1 ELSE 0 END) as pending')
            )
            ->where('tickets.date', '>=', $sixMonthsAgo)
            ->where('tickets.company_id', Auth::user()->current_company_id)
            ->groupBy('items.name', 'items.id')
            ->orderBy('items.id')
            ->get();
        
        // Preparar datos para el gráfico
        $this->labels = $ticketsByPriority->pluck('priority')->toArray();
        $this->resolvedData = $ticketsByPriority->pluck('resolved')->toArray();
        $this->pendingData = $ticketsByPriority->pluck('pending')->toArray();
        
        // Crear datasets para Chart.js
        $datasets = [
            [
                'label' => 'Resueltos',
                'data' => $this->resolvedData,
                'backgroundColor' => $this->colors['resolved'],
                'borderColor' => 'rgba(75, 192, 192, 1)',
                'borderWidth' => 1,
            ],
            [
                'label' => 'Pendientes',
                'data' => $this->pendingData,
                'backgroundColor' => $this->colors['pending'],
                'borderColor' => 'rgba(255, 99, 132, 1)',
                'borderWidth' => 1,
            ]
        ];
        
        // Formatear datos para Chart.js
        $this->chartData = [
            'labels' => $this->labels,
            'datasets' => $datasets,
        ];
    }
    
    public function render()
    {
        return view('livewire.grap.ticket-priority-grap');
    }
}
