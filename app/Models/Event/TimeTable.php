<?php

namespace App\Models\Event;

use App\Models\Config\Item;
use App\Models\Master\Company;
use App\Models\Master\Person;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class TimeTable extends Model
{
    use LogsActivity;
    
    protected $fillable = [
        'date',
        'item_id',
        'person_id',
        'user_id',
        'company_id',
        'text',
        'cant',
        'percentage',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function person()
    {
        return $this->belongsTo(Person::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
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
