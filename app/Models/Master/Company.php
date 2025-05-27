<?php

namespace App\Models\Master;

use App\Models\Project\Project;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Company extends Model
{
    use LogsActivity;
    
    protected $fillable = [
        'name',
        'prefix',
        'address',
        'phone',
        'email',
        'head1',
        'head2',
        'head3',
        'foot1',
        'foot2',
        'foot3',
        'state',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class)->withPivot('role_id');
    }

    public function projects()
    {
        return $this->hasMany(Project::class);
    }    

    public function people() 
    {
        return $this->belongsToMany(Person::class);
    }

    public function units() 
    {
        return $this->belongsToMany(Unit::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}
