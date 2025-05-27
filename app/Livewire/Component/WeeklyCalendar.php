<?php

namespace App\Livewire\Component;

use App\Models\Event\TimeTable;
use App\Models\Project\Activity;
use App\Models\Project\Schedule;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class WeeklyCalendar extends Component
{
    public $currentDate;
    public $weeks = [];
    public $activities = [];
    public $timeTableEvents = [];
    public $schedules = [];
    public $menuId = 402;

    protected $listeners = ['refreshCalendar' => 'refresh'];

    public function mount()
    {
        $this->currentDate = Carbon::now();
        $this->loadWeeks();
        $this->loadEvents();
    }

    public function loadWeeks()
    {
        $this->weeks = [
            'previous' => $this->generateWeekDates($this->currentDate->copy()->subWeek()),
            'current' => $this->generateWeekDates($this->currentDate->copy()),
            'next' => $this->generateWeekDates($this->currentDate->copy()->addWeek())
        ];
    }

    private function generateWeekDates(Carbon $date)
    {
        $startOfWeek = $date->copy()->startOfWeek(Carbon::MONDAY);
        $week = [];

        for ($i = 0; $i < 7; $i++) {
            $dayDate = $startOfWeek->copy()->addDays($i);
            $week[] = [
                'date' => $dayDate,
                'isPast' => $dayDate->isPast() && !$dayDate->isToday(),
                'isToday' => $dayDate->isToday(),
                'isFuture' => $dayDate->isFuture() && !$dayDate->isToday(),
                'formatted' => $dayDate->format('d M Y'),
                'dayName' => $dayDate->isoFormat('ddd'),
                'dateString' => $dayDate->format('Y-m-d')
            ];
        }

        return $week;
    }

    public function loadEvents()
    {
        $calendarStartDate = $this->weeks['previous'][0]['date']->copy()->startOfDay();
        $calendarEndDate = $this->weeks['next'][6]['date']->copy()->endOfDay();

        // Cargar y formatear actividades
        $this->activities = $this->getPendingActivities($calendarStartDate, $calendarEndDate)
            ->map(function ($activity) {
                $activity->end_date = Carbon::parse($activity->end_date);
                $activity->dateString = $activity->end_date->format('Y-m-d');
                return $activity;
            });

        // Cargar y formatear schedules
        $this->schedules = $this->getPendingSchedules($calendarStartDate, $calendarEndDate)
            ->map(function ($schedule) {
                $effectiveDate = Carbon::parse($schedule->date)->addDays($schedule->days);
                $schedule->effective_date = $effectiveDate;
                $schedule->dateString = $effectiveDate->format('Y-m-d');
                return $schedule;
            });

        // Cargar y formatear eventos de TimeTable
        $this->timeTableEvents = $this->getTimeTableEvents($calendarStartDate, $calendarEndDate)
            ->map(function ($event) {
                $event->date = Carbon::parse($event->date_tt);
                $event->dateString = $event->date->format('Y-m-d');
                $event->occupancy_percentage = $event->activity_capacity;
                return $event;
            });
    }

    private function getPendingActivities(Carbon $startDate, Carbon $endDate)
    {
        $advancedQuantities = DB::table('mvtos')
            ->select(
                'mvtos.activity_id',
                DB::raw('COALESCE(SUM(mvtos.cant), 0) as advanced_quantity')
            )
            ->join('docs', 'docs.id', '=', 'mvtos.doc_id')
            ->where('docs.menu_id', $this->menuId)
            ->where('mvtos.state', 1)
            ->where('docs.state', 1)
            ->groupBy('mvtos.activity_id');

        return Activity::query()
            ->select([
                'activities.id', 'activities.code', 'activities.name as activity_name', 'activities.end_date', 'activities.cant', 'activities.project_id', 'activities.unit_id',
                'projects.name as project_name',
                'units.name as unit_name',
                DB::raw('COALESCE(aq.advanced_quantity, 0) as advanced_quantity'),
                DB::raw('activities.cant - COALESCE(aq.advanced_quantity, 0) as pending_quantity')
            ])
            ->join('projects', 'projects.id', '=', 'activities.project_id')
            ->leftJoin('units', 'units.id', '=', 'activities.unit_id')
            ->leftJoinSub($advancedQuantities, 'aq', function($join) {
                $join->on('activities.id', '=', 'aq.activity_id');
            })
            ->where('activities.state', 1)
            ->where('projects.state', 1)
            ->where('activities.user_id', Auth::id())
            ->where('projects.company_id', Auth::user()->current_company_id)
            ->whereBetween('activities.end_date', [$startDate->toDateString(), $endDate->toDateString()])
            ->having('pending_quantity', '>', 0)
            ->orderBy('activities.end_date', 'asc')
            ->orderBy('projects.name')
            ->orderBy('activities.code')
            ->get();
    }

    private function getPendingSchedules(Carbon $startDate, Carbon $endDate)
    {
        $maxExpectedDays = 90;

        return Schedule::query()
            ->where('schedules.company_id', Auth::user()->current_company_id)
            ->where('schedules.state', 1)
            ->where('schedules.saldo', '>', 0)
            ->where('schedules.date', '<=', $endDate->toDateString())
            ->where('schedules.date', '>=', $startDate->copy()->subDays($maxExpectedDays)->toDateString())
            ->whereRaw('DATE_ADD(schedules.date, INTERVAL schedules.days DAY) BETWEEN ? AND ?', [
                $startDate->toDateString(),
                $endDate->toDateString()
            ])
            ->join('projects', 'projects.id', '=', 'schedules.project_id')
            ->join('spaces', 'spaces.id', '=', 'schedules.space_id')
            ->select([
                'schedules.date', 
                'schedules.days',
                'projects.name as project_name',
                'spaces.name as space_name'
            ])
            ->orderByRaw('DATE_ADD(schedules.date, INTERVAL schedules.days DAY)')
            ->orderBy('projects.name')
            ->orderBy('spaces.name')
            ->get();
    }

    private function getTimeTableEvents(Carbon $startDate, Carbon $endDate)
    {
        return TimeTable::query()
            ->join('items as activity_item', 'time_tables.item_id', '=', 'activity_item.id')
            ->select([
                'time_tables.date as date_tt',
                'time_tables.item_id as activity_id',
                'activity_item.name as activity_name',
                'time_tables.percentage as activity_capacity',
                'time_tables.cant as people_count'
            ])
            ->where('time_tables.company_id', Auth::user()->current_company_id)
            ->where('time_tables.user_id', Auth::id())
            ->whereBetween('time_tables.date', [$startDate->toDateString(), $endDate->toDateString()])
            ->orderBy('time_tables.date', 'asc')
            ->orderBy('activity_item.name', 'asc')
            ->get();
    }

    public function refresh()
    {
        $this->loadEvents();
        $this->dispatch('events-loaded');
    }

    public function render()
    {
        return view('livewire.component.weekly-calendar', [
            'formattedActivities' => $this->activities,
            'formattedSchedules' => $this->schedules,
            'formattedTimeTableEvents' => $this->timeTableEvents
        ]);
    }
}