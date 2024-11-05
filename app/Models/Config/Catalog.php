<?php

namespace App\Models\Config;

use Illuminate\Database\Eloquent\Model;

class Catalog extends Model
{
    protected $fillable = ['id', 'name', 'text'];

    public function items()
    {
        return $this->hasMany(Item::class);
    }
}
