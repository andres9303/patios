<div>
    <div class="p-6 bg-white rounded-lg shadow-md">
        <h2 class="text-xl font-semibold mb-4 text-gray-400">Subcategorías de Tickets más Solicitadas (Últimos 3 Meses)</h2>
        <div class="chart-container" style="position: relative; height:400px; width:100%">
            <canvas id="ticketCategoryChart"></canvas>
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
            const ctx = document.getElementById('ticketCategoryChart').getContext('2d');
            
            const ticketCategoryChart = new Chart(ctx, {
                type: 'bar',
                data: chartData,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Cantidad de Tickets'
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: 'Subcategorías'
                            },
                            ticks: {
                                maxRotation: 45,
                                minRotation: 30, // Aumenté la rotación mínima para mejor legibilidad
                                autoSkip: true,
                                maxTicksLimit: 10,
                                padding: 10,
                                font: {
                                    size: 12,
                                    weight: 'normal'
                                }
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return `Total: ${context.raw}`;
                                }
                            }
                        },
                        // Configuración del plugin datalabels
                        datalabels: {
                            anchor: 'end', // Posición: 'start', 'center' o 'end'
                            align: 'top',  // Alineación: 'top', 'center', 'bottom'
                            color: '#444', // Color del texto
                            font: {
                                weight: 'bold',
                                size: 12
                            },
                            formatter: function(value) {
                                return value > 0 ? value : ''; // Solo muestra valores mayores a 0
                            },
                            offset: -5, // Espacio adicional entre el texto y la barra
                            clip: false // Permite que el texto se muestre fuera de la barra
                        }
                    }
                },
                plugins: [ChartDataLabels] // Registra el plugin
            });
            
            // Actualización del gráfico cuando cambian los datos
            @this.on('chartDataUpdated', (newData) => {
                ticketCategoryChart.data = newData;
                ticketCategoryChart.update();
            });
        }
    });
</script>
@endpush