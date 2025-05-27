<div class="weekly-calendar-container">
    <div class="calendar-controls mb-4">
        <span class="bg-blue-800 text-blue-100 p-1 rounded text-xs"><i class="fas fa-ruler mr-1"></i>Actividades pendientes</span>
        <span class="bg-gray-800 text-gray-100 p-1 rounded text-xs"><i class="fas fa-calendar-alt mr-1"></i>Proyectos pendientes de programación</span>
        <span class="bg-purple-700 text-purple-100 p-1 rounded text-xs"><i class="fab fa-untappd mr-1"></i>Agenda de actividades</span>
    </div>

    <div class="calendar-weeks">
        @foreach(['previous' => 'Semana Anterior', 'current' => 'Semana Actual', 'next' => 'Próxima Semana'] as $weekKey => $weekTitle)
        <div class="calendar-week mb-5">
            <h4 class="week-title">{{ $weekTitle }}</h4>
            <div class="week-days grid grid-cols-7 gap-2">
                @foreach($weeks[$weekKey] as $day)
                <div class="day-card p-3 rounded-lg border 
                    @if($day['isPast']) bg-red-100 @endif
                    @if($day['isToday']) bg-yellow-100 @endif
                    @if($day['isFuture']) bg-green-50 @endif">
                    
                    <div class="day-header mb-2">
                        <div class="font-bold">{{ $day['dayName'] }}</div>
                        <div class="text-sm">{{ $day['formatted'] }}</div>
                    </div>

                    <div class="day-events space-y-2">
                        <!-- Actividades Pendientes -->
                        @foreach($formattedActivities->where('dateString', $day['dateString']) as $activity)
                        <div class="event bg-blue-800 text-blue-100 p-1 rounded text-xs">
                            <a href="{{ route('advance-project.create') }}"><i class="fas fa-ruler mr-1"></i>{{ $activity->project_name }} [{{ number_format($activity->pending_quantity, 2) }} pendientes]</a>
                        </div>
                        @endforeach

                        <!-- Proyectos Pendientes -->
                        @foreach($formattedSchedules->where('dateString', $day['dateString']) as $schedule)
                        <div class="event bg-gray-800 text-gray-100 p-1 rounded text-xs">
                            <a href="{{ route('project.index') }}"><i class="fas fa-calendar-alt mr-1"></i>{{ $schedule->project_name }} [{{ $schedule->space_name }}]</a>
                        </div>
                        @endforeach

                        <!-- TimeTable Events -->
                        @foreach($formattedTimeTableEvents->where('dateString', $day['dateString']) as $ttEvent)
                        <div class="event bg-purple-700 text-purple-100 p-1 rounded text-xs">
                            <a href="{{ route('timetable.index') }}"><i class="fab fa-untappd mr-1"></i>{{ $ttEvent->activity_name }} [<i class="fas fa-users mr-1"></i>{{ number_format($ttEvent->people_count) }} / {{ number_format($ttEvent->occupancy_percentage) }}%]</a>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endforeach
    </div>
</div>

@push('styles')
<style>
    .weekly-calendar-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 1rem;
    }
    .week-days {
        grid-template-columns: repeat(7, minmax(0, 1fr));
    }
    .day-card {
        min-height: 150px;
        transition: all 0.2s ease;
    }
    .day-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
    .event {
        font-size: 0.8rem;
        margin-bottom: 2px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('livewire:load', function() {
        Livewire.on('events-loaded', () => {
            // Puedes agregar cualquier lógica JS que necesites después de actualizar
            console.log('Eventos actualizados');
        });
    });
</script>
@endpush