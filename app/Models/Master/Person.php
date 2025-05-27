<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Person extends Model
{
    use LogsActivity;
    
    protected $table = 'people';
    
    protected $fillable = [
        'identification',
        'name',
        'email',
        'phone',
        'address',
        'whatsapp',
        'telegram',
        'text',
        'birth',
        'isClient',
        'isSupplier',
        'isOperator',
        'isEmployee',
        'state',
    ];

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
