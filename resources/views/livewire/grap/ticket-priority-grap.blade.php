<div>
    <div class="p-6 bg-white rounded-lg shadow-md">
        <h2 class="text-xl font-semibold mb-4 text-gray-400">Estado de Tickets por Prioridad (Últimos 6 Meses)</h2>
        <div class="chart-container" style="position: relative; height:400px; width:100%">
            <canvas id="ticketPriorityChart"></canvas>
        </div>
    </div>
</div>

@push('scripts')
<!-- Incluye el plugin de datalabels -->
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>

<script>
    document.addEventListener('livewire:initialized', () => {
        const chartData = @json($chartData);
        
        if (chartData && chartData.labels.length > 0) {
            const ctx = document.getElementById('ticketPriorityChart').getContext('2d');
            
            const ticketPriorityChart = new Chart(ctx, {
                type: 'bar',
                data: chartData,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            stacked: true,
                            title: {
                                display: true,
                                text: 'Cantidad de Tickets'
                            }
                        },
                        x: {
                            stacked: true,
                            title: {
                                display: true,
                                text: 'Nivel de Prioridad'
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top'
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const label = context.dataset.label || '';
                                    const value = context.raw || 0;
                                    return `${label}: ${value}`;
                                }
                            }
                        },
                        datalabels: {
                            anchor: 'center',
                            align: 'center',
                            color: '#444',
                            font: {
                                weight: 'bold',
                                size: 12
                            },
                            formatter: function(value, context) {
                                return value !== 0 ? value : ''; // Mostrar solo si no es cero
                            }
                        }
                    }
                },
                plugins: [ChartDataLabels] // Registra el plugin
            });

            @this.on('chartDataUpdated', (newData) => {
                ticketPriorityChart.data = newData;
                ticketPriorityChart.update();
            });
        }
    });
</script>
@endpush