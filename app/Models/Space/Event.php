<?php

namespace App\Models\Space;

use App\Models\Config\Item;
use App\Models\Doc;
use App\Models\Master\Company;
use App\Models\Master\Space;
use App\Models\Mvto;
use App\Models\Security\Menu;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Event extends Model
{
    use LogsActivity;
    
    protected $fillable = [
        'title',
        'text',
        'company_id',
        'item_id',
        'space_id',
        'menu_id',
        'doc_id',
        'mvto_id',
        'date',
        'time',
        'location',
        'state',
        'user_id',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function space()
    {
        return $this->belongsTo(Space::class);
    }

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    public function doc()
    {
        return $this->belongsTo(Doc::class);
    }

    public function mvto()
    {
        return $this->belongsTo(Mvto::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}
