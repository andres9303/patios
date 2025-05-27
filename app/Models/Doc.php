<?php

namespace App\Models;

use App\Models\Config\Item;
use App\Models\Master\Company;
use App\Models\Master\Person;
use App\Models\Master\Space;
use App\Models\Project\Project;
use App\Models\Security\Menu;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Doc extends Model
{
    use LogsActivity;
    
    protected $table = 'docs';
    protected $fillable = ['menu_id', 'company_id', 'code', 'num', 'date', 'date2', 'date3', 'date4', 'subtotal', 'iva', 'discount', 'total', 'state', 'text', 'concept', 'ref', 'person_id', 'person2_id', 'space_id', 'item_id', 'doc_id', 'user_id', 'cant', 'saldo', 'value'];

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function person()
    {
        return $this->belongsTo(Person::class);
    }

    public function person2()
    {
        return $this->belongsTo(Person::class);
    }

    public function space()
    {
        return $this->belongsTo(Space::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function doc()
    {
        return $this->belongsTo(Doc::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function mvtos()
    {
        return $this->hasMany(Mvto::class);
    }

    public function project_ref()
    {
        return $this->belongsTo(Project::class, 'ref', 'id');
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
