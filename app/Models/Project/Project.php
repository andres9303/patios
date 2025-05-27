<?php

namespace App\Models\Project;

use App\Models\Config\Item;
use App\Models\Master\Company;
use App\Models\Master\Space;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Project extends Model
{
    use LogsActivity;
    
    protected $table = 'projects';
    protected $fillable = ['company_id', 'name', 'text', 'state', 'concept', 'type', 'item_id', 'space_id', 'schedule_id'];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function space()
    {
        return $this->belongsTo(Space::class);
    }

    public function activities()
    {
        return $this->hasMany(Activity::class);
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}
