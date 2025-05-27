<?php

namespace App\Models\Project;

use App\Models\Master\Unit;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Activity extends Model
{
    use LogsActivity;
    
    protected $table = 'activities';
    protected $fillable = ['project_id', 'code', 'name', 'unit_id', 'text', 'state', 'cant', 'value', 'cost', 'start_date', 'end_date', 'type', 'activity_id', 'user_id'];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}
