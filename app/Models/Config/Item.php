<?php

namespace App\Models\Config;

use App\Models\Master\Space;
use App\Models\Project\Project;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Item extends Model
{
    use LogsActivity;
    
    protected $fillable = ['id', 'name', 'text', 'order', 'factor', 'catalog_id', 'item_id'];

    public function catalog()
    {
        return $this->belongsTo(Catalog::class);
    }

    public function ref()
    {
        return $this->belongsTo(Item::class);
    }

    public function spaces()
    {
        return $this->hasMany(Space::class);
    }

    public function spaces2()
    {
        return $this->hasMany(Space::class);
    }

    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}
