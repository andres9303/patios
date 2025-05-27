<?php

namespace App\Models\Master;

use App\Models\Config\Item;
use App\Models\Project\Schedule;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Space extends Model
{
    use LogsActivity;
    
    protected $table = 'spaces';
    protected $fillable = [
        'name',
        'text',
        'order',
        'state',
        'space_id',
        'item_id',
        'item2_id',
        'cant',
        'company_id',
    ];

    public function parentSpace()
    {
        return $this->belongsTo(Space::class, 'space_id');
    }

    public function childSpaces()
    {
        return $this->hasMany(Space::class, 'space_id');
    }
    
    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function item2()
    {
        return $this->belongsTo(Item::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
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
