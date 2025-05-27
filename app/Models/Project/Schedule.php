<?php

namespace App\Models\Project;

use App\Models\Master\Company;
use App\Models\Master\Space;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Schedule extends Model
{
    use LogsActivity;
    
    protected $table = 'schedules';
    protected $fillable = [
        'project_id',
        'space_id',
        'company_id',
        'date',
        'days',
        'cant',
        'saldo',
        'text',
        'state',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function space()
    {
        return $this->belongsTo(Space::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}
