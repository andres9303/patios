<?php

namespace App\Models\Ticket;

use App\Models\Attachment;
use App\Models\Master\Category;
use App\Models\Master\Company;
use App\Models\Master\Location;
use App\Models\Master\Person;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Ticket extends Model
{
    use LogsActivity;
    
    protected $fillable = [
        'date',
        'date2',
        'date3',
        'name',
        'company_id',
        'person_id',
        'location_id',
        'category_id',
        'category2_id',
        'item_id',
        'text',
        'state',
        'user_id',
        'user2_id',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function person()
    {
        return $this->belongsTo(Person::class, 'person_id');
    }

    public function location()
    {
        return $this->belongsTo(Location::class, 'location_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function category2()
    {
        return $this->belongsTo(Category::class, 'category2_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function user2()
    {
        return $this->belongsTo(User::class, 'user2_id');
    }

    public function trackings()
    {
        return $this->hasMany(Tracking::class, 'ticket_id');
    }

    public function attachments()
    {
        return $this->morphMany(Attachment::class, 'attachmentable');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}
