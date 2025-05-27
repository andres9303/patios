<?php

namespace App\Models\Space;

use App\Models\Master\Company;
use App\Models\Master\Space;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class CheckList extends Model
{
    use LogsActivity;
    
    protected $fillable = [
        'template_id',
        'space_id',
        'company_id',
        'user_id',
        'date',
        'state',
    ];

    public function template()
    {
        return $this->belongsTo(Template::class);
    }
    
    public function space()
    {
        return $this->belongsTo(Space::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function answers()
    {
        return $this->hasMany(Answer::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}
