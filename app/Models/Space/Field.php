<?php

namespace App\Models\Space;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Field extends Model
{
    use LogsActivity;
    
    protected $fillable = [
        'name',
        'description',
        'template_id',
        'type_field_id',
        'is_description',
        'is_required',
        'order',
        'state',
    ];

    public function template()
    {
        return $this->belongsTo(Template::class);
    }

    public function typeField()
    {
        return $this->belongsTo(TypeField::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}
