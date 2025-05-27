<?php

namespace App\Models\Config;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Variable extends Model
{
    use LogsActivity;
    
    protected $fillable = [
        'cod',
        'name',
        'text',
        'concept',
        'value',
        'variable_id'
    ];

    public function variable()
    {
        return $this->belongsTo(Variable::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}
