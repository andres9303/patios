<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    protected $table = 'locations';
    protected $fillable = [
        'code',
        'name',
        'text',
        'ref_id',
        'company_id',
        'state',
    ];

    public function parent()
    {
        return $this->belongsTo(Location::class, 'ref_id');
    }

    public function children()
    {
        return $this->hasMany(Location::class, 'ref_id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
