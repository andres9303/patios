<?php

namespace App\Models\Master;

use App\Models\Project\Activity;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Unit extends Model
{
    use LogsActivity;
    
    protected $table = 'units';
    protected $fillable = [
        'name',
        'unit',
        'time',
        'mass',
        'longitude',
        'state',
        'unit_id',
        'factor',
    ];

    public function parent()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }

    public function child()
    {
        return $this->hasMany(Unit::class, 'unit_id');
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'product_id');
    }

    public function activities()
    {
        return $this->hasMany(Activity::class, 'unit_id');
    }

    public function companies() 
    {
        return $this->belongsToMany(Company::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}
