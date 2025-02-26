<?php

namespace App\Models\Project;

use App\Models\Master\Unit;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    protected $table = 'activities';
    protected $fillable = ['project_id', 'code', 'name', 'unit_id', 'text', 'state', 'cant', 'value', 'start_date', 'end_date', 'type', 'activity_id'];

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
}
