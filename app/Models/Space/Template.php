<?php

namespace App\Models\Space;

use App\Models\Master\Company;
use App\Models\Master\Space;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Template extends Model
{
    use LogsActivity;
    
    protected $fillable = [
        'name',
        'description',
        'state',
        'company_id',
        'space_id',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function space()
    {
        return $this->belongsTo(Space::class);
    }

    public function fields()
    {
        return $this->hasMany(Field::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}
