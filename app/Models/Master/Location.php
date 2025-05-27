<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Location extends Model
{
    use LogsActivity;
    
    protected $table = 'locations';
    protected $fillable = [
        'code',
        'name',
        'text',
        'ref_id',
        'company_id',
        'state',
    ];

    public function parent()
    {
        return $this->belongsTo(Location::class, 'ref_id');
    }

    public function children()
    {
        return $this->hasMany(Location::class, 'ref_id');
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
