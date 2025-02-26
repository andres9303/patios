<?php

namespace App\Models\Project;

use App\Models\Config\Item;
use App\Models\Master\Company;
use App\Models\Master\Space;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $table = 'projects';
    protected $fillable = ['company_id', 'name', 'text', 'state', 'concept', 'type', 'item_id', 'space_id'];

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

    public function activities()
    {
        return $this->hasMany(Activity::class);
    }
}
