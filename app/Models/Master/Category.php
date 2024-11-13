<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'categories';
    protected $fillable = [
        'code',
        'name',
        'text',
        'days',
        'ref_id',
        'company_id',
        'state',
    ];

    public function parent()
    {
        return $this->belongsTo(Category::class, 'ref_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'ref_id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
