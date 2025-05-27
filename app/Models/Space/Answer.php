<?php

namespace App\Models\Space;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Answer extends Model
{
    use LogsActivity;
    
    protected $fillable = [
        'check_list_id',
        'field_id',
        'value_text',
        'value_number',
        'value_decimal',
        'value_date',
        'value_time',
        'value_datetime',
        'value_boolean',
        'description',
    ];

    public function field()
    {
        return $this->belongsTo(Field::class);
    }
    
    public function checkList()
    {
        return $this->belongsTo(CheckList::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}
